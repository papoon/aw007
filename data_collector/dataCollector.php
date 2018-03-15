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
      private $numberElements;
      const ARTICLE_FROM_DATE = '2017/01/01';
      const NULL = 'NULL';

      //generic construct
      //$numberElements is the number of elements of each type that should
      //be able to be shown in the application (e.g. photos, tweets) for each
      //disease;
      public function __construct($numberDiseases, $numberElements) {
          $this->connector = DbConnector::defaultConnection();
          $this->dbLink = $this->connector->connect();
          $this->numberDiseases = $numberDiseases;
          $this->numberElements = $numberElements;
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
                    $publishedAt = $publishedAt->format('Y-m-d H:i:s');
                  } catch(Exception $e){
                    //in case date is not found
                    $publishedAt = self::NULL;
                  }

                  try{
                    $articleDate = new DateTime($pubmedFeach->getArticleDate());
                    $articleDate = $articleDate->format('Y-m-d H:i:s');
                  } catch(Exception $e){
                    //in case date is not found
                    $articleDate = self::NULL;
                  }

                  try{
                    $articleRevisionDate = new DateTime($pubmedFeach->getArticleRevisionDate());
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
          //TODO
          //get photos information for each disease
          // foreach($diseaseIdsNames as $did=>$diseaseName) {
          //   $flickr = new Flickr($diseaseName);
          //   $photos = $flickr->getResponse();
          //   $photosUrl = $flickr->getPhotosUrl();
          //
          //   $i = 0;
          //   foreach($photosUrl as $key=>$photo) {
          //
          //     //escape text for apostrofes and other string breakers (security issues)
          //     $owner = mysqli_real_escape_string($this->dbLink, $photos['photos']['photo'][$i]['owner']);
          //
          //     $values = array($did,                                     //did
          //                     $photo[0],                                //url
          //                     $key,                                     //flicrk_id
          //                     "Unknown",                                //author_name
          //                     $owner,                                   //username
          //                     -1,                                       //nr_likes
          //                     -1,                                       //nr_comments
          //                     -1,                                       //shares
          //                     "Unknown",                                //country
          //                     "0000-00-00 00:00:00",                    //published_at
          //                     $currentDateStr,                          //inserted_at
          //                     $currentDateStr);                         //updated_at
          //
          //     //create array of values to insert in the database
          //     $toInsert = createInsertArray(TABLE_PHOTOS, $values);
          //     //insert photo in the database
          //     $this->connector->insertInto(TABLE_PHOTOS, $toInsert);
          //
          //   }
          //
          // }

          //**************table Tweets*****************
          //TODO
          foreach($diseaseIdsNames as $did=>$diseaseName) {
            $twitter = new TwitterSearch($diseaseName);
            $tweets = $twitter->getTweets();
          }

          //close db connection
          $this->connector->disconnect();
      }

    }
?>
