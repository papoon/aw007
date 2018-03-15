<?php
    include_once '../database/dbConnector.php';
    include_once '../database/dbUtils.php';
    include_once "../webservices/dbpediaDiseases.php";
    include_once "../webservices/pubmedSearch.php";
    include_once "../webservices/pubmedFeach.php";
    include_once "../webservices/flickr.php";
    include_once "../webservices/twitterSearch.php";
    include_once "../webservices/twitterEmbed.php";

    class DataCollector {

      private $connector;
      private $dbLink;
      private $numberDiseases;
      const ARTICLE_FROM_DATE = '2017/01/01';
      const NULL = 'NULL';

      //generic construct
      public function __construct($numberDiseases) {
          $this->connector = DbConnector::defaultConnection();
          $this->dbLink = $this->connector->connect();
          $this->numberDiseases = $numberDiseases;
      }

      //**************WIP************
      //use this operation when database is empty
      public function getAllData() {

          //**************table Disease*****************
          //get the information for the needed number of diseases
          $dbPediaDiseases = new DBPediaDiseases($this->numberDiseases);
          $diseases = $dbPediaDiseases->getDiseases();
          $diseaseIdsNames = [];

          //get current date
          $currentDate = new DateTime();
          $currentDateStr = $currentDate->format('Y-m-d H:i:s');

          //insert disease information in the database
          foreach($diseases as $disease){

            //escape text for apostrofes and other string breakers (security issues)
            $diseaseName = mysqli_real_escape_string($this->dbLink, $disease['label']['value']);

            echo "Getting information for disease: ".$diseaseName.PHP_EOL;

            $abstract = mysqli_real_escape_string($this->dbLink, $disease['abstract']['value']);

            $values = array($diseaseName,                             //name
                            $disease['wikiPageID']['value'],          //dbpedia_id
                            $disease['wikiPageRevisionID']['value'],  //dbpedia_revision_id
                            $abstract,                                //abstract
                            $disease['thumbnail']['value'],           //thumbnail
                            $disease['name']['value'],                //uri
                            $currentDateStr,                          //created_at
                            $currentDateStr);                         //updated_at

            //create array of values to insert in the database
            $toInsert = createInsertArray(TABLE_DISEASE, $values);
            //insert disease in the database
            $this->connector->insertInto(TABLE_DISEASE, $toInsert);

            //save diseases ids and names for next phase (key value pair)
            $diseaseIdsNames[getLastInsertId($this->dbLink)] = $diseaseName;

            #echo getLastInsertId($this->dbLink)."|".$disease['label']['value'];
          }

          #echo implode("|", $diseaseIdsNames);

          //get pubmed articles information for each disease
          foreach($diseaseIdsNames as $did=>$diseaseName) {

              echo "Getting articles for disease: ".$diseaseName.PHP_EOL;

              //getting 10 (random?) article ids
              $pubmed = new PubMedSearch($diseaseName);
              $articleIds = $pubmed->getIdListsByDate(self::ARTICLE_FROM_DATE)['Id'];

              $countArticles = 1;

              //get information for each article with given id
              foreach($articleIds as $articleId) {

                  echo "  Getting information for article with id: ".$articleId.PHP_EOL;

                  //**************table Article*****************
                  $pubmedFeach = new PubMedFeach($articleId);
                  $article = $pubmedFeach->getResponse();

                  //set default value for abstract string (what is kept in case of no abstract)
                  $abstract = 'No abstract found.';
                  try{
                      $abstract = $pubmedFeach->getArticleAbstract();
                      //escape text for apostrofes and other string breakers (security issues)
                      $abstract = mysqli_real_escape_string($this->dbLink, $abstract);
                  } catch(Exception $e){
                      //do nothing
                  }

                  //escape text for apostrofes and other string breakers (security issues)
                  $articleTitle = mysqli_real_escape_string($this->dbLink, $pubmedFeach->getArticleTitle());

                  //converting date formats to mysql format
                  try{
                    $publishedAt = new DateTime($pubmedFeach->getArticleJournalPubDate());
                    $publishedAt->setTime(0,0,0);
                    $publishedAt = $publishedAt->format('Y-m-d H:i:s');
                  } catch(Exception $e){
                    //in case date is not found
                    $publishedAt = self::NULL;
                  }

                  try{
                    $articleDate = new DateTime($pubmedFeach->getArticleDate());
                    $articleDate->setTime(0,0,0);
                    $articleDate = $articleDate->format('Y-m-d H:i:s');
                  } catch(Exception $e){
                    //in case date is not found
                    $articleDate = self::NULL;
                  }

                  try{
                    $articleRevisionDate = new DateTime($pubmedFeach->getArticleRevisionDate());
                    $articleRevisionDate->setTime(0,0,0);
                    $articleRevisionDate = $articleRevisionDate->format('Y-m-d H:i:s');
                  } catch(Exception $e){
                    //in case date is not found
                    $articleRevisionDate = self::NULL;
                  }

                  //join authors names with pipes
                  //escape text for apostrofes and other string breakers (security issues)
                  $authors = $pubmedFeach->getArticleAuthors();
                  $authors = implode('|', $authors);
                  $authors = mysqli_real_escape_string($this->dbLink, $authors);

                  $values = array($did,                                     //did
                                  $articleId,                               //article_id
                                  $pubmedFeach->getArticleJournalId(),      //journal_id
                                  $articleTitle,                            //title
                                  $abstract,                                //abstract
                                  $publishedAt,                             //published_at
                                  $articleDate,                             //article_date
                                  $articleRevisionDate,                     //article_revision_date
                                  $authors,                                 //authors
                                  $currentDateStr,                          //inserted_at
                                  $currentDateStr);                         //updated_at

                  //create array of values to insert in the database
                  $toInsert = createInsertArray(TABLE_ARTICLE, $values);
                  //insert article in the database
                  $this->connector->insertInto(TABLE_ARTICLE, $toInsert);
                  //save article id for later use
                  $dbArticleId = getLastInsertId($this->dbLink);
              }
          }


          //**************table Photos*****************
          //get photos information for each disease
          foreach($diseaseIdsNames as $did=>$diseaseName) {

            echo "Getting photos for disease: ".$diseaseName.PHP_EOL;

            $flickr = new Flickr($diseaseName);
            $photos = $flickr->getResponse();
            $photosUrl = $flickr->getPhotosUrl();

            $i = 0;
            foreach($photosUrl as $key=>$photo) {

              echo "  Getting information for photo with id: ".$key.PHP_EOL;

              $values = array($did,                                     //did
                              $photo[0],                                //url
                              $key,                                     //flicrk_id
                              "Unknown",                                //author_name
                              "Unknown",                                //username
                              0,                                        //nr_likes
                              0,                                        //nr_comments
                              0,                                        //shares
                              "Unknown",                                //country
                              self::NULL,                               //published_at
                              $currentDateStr,                          //inserted_at
                              $currentDateStr);                         //updated_at

              //create array of values to insert in the database
              $toInsert = createInsertArray(TABLE_PHOTOS, $values);
              //insert photo in the database
              $this->connector->insertInto(TABLE_PHOTOS, $toInsert);

            }

          }

          //**************table Tweets*****************
          //get tweets information for each disease
          foreach($diseaseIdsNames as $did=>$diseaseName) {

            echo "Getting tweets for disease: ".$diseaseName.PHP_EOL;

            //get tweets from the last 7 days
            $twitter = new TwitterSearch($diseaseName);
            $tweets = $twitter->getTweets();

            foreach($tweets as $tweet) {

              $tweetId = $twitter->getTweetId($tweet);

              echo "  Getting information for tweet with id: ".$tweetId.PHP_EOL;

              //create twitter embed url
              $twitter_embed = new TwitterEmbed($tweetId);
              $twitter_embed = $twitter_embed->getResponse();

              //escape text for apostrofes and other string breakers (security issues)
              $authorName = mysqli_real_escape_string($this->dbLink, $twitter->getAuthorName($tweet));
              $userName = mysqli_real_escape_string($this->dbLink, $twitter->getUsername($tweet));

              //set default value for location string (what is kept in case of no location
              $location = $twitter->getAuthorLocation($tweet);
              if ($location == '') {
                $location = 'Unknown';
              } else {
                //escape text for apostrofes and other string breakers (security issues)
                $location = mysqli_real_escape_string($this->dbLink, $location);
              }

              //converting date formats to mysql format
              try{
                $tweetPublishedDate = new DateTime($twitter->getPublishedDate($tweet));
                $tweetPublishedDate->setTime(0,0,0);
                $tweetPublishedDate = $tweetPublishedDate->format('Y-m-d H:i:s');
              } catch(Exception $e){
                //in case date is not found
                $tweetPublishedDate = self::NULL;
              }

              $values = array($did,                                     //did
                              $twitter_embed['url'],                    //url
                              self::NULL,                               //type
                              $tweetId,                                 //tweet_id
                              $authorName,                              //author_name
                              $userName,                                //username
                              $twitter->getNumberOfLikes($tweet),       //nr_likes
                              0,                                        //nr_comments
                              $twitter->getNumberOfShares($tweet),      //shares
                              $location,                                //country
                              $tweetPublishedDate,                      //published_at
                              $currentDateStr,                          //inserted_at
                              $currentDateStr);                         //updated_at

              //create array of values to insert in the database
              $toInsert = createInsertArray(TABLE_TWEETS, $values);

              //insert tweet in the database
              $this->connector->insertInto(TABLE_TWEETS, $toInsert);

            }
          }

          //close db connection
          $this->connector->disconnect();
      }

    }
?>
