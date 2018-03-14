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
              //getting 10 (random?) article ids
              $pubmed = new PubMedSearch($diseaseName, 2);
              $articleIds = $pubmed->getIdLists()['Id'];

              //list for pairs (article, author)
              $articleAuthors = [];
              //set of authors to insert in the database at the end of this phase
              $setAuthors = [];

              //get information for each article with given id
              foreach($articleIds as $articleId) {
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
                  $publishedAt = new DateTime($pubmedFeach->getArticleJournalPubDate());
                  $publishedAt = $publishedAt->format('Y-m-d H:i:s');

                  $articleDate = new DateTime($pubmedFeach->getArticleDate());
                  $articleDate = $articleDate->format('Y-m-d H:i:s');

                  $articleRevisionDate = new DateTime($pubmedFeach->getArticleRevisionDate());
                  $articleRevisionDate = $articleRevisionDate->format('Y-m-d H:i:s');

                  $values = array($did,                                     //did
                                  $pubmedFeach->getArticleJournalId(),      //journal_id
                                  $articleTitle,                            //title
                                  $abstract,                                //abstract
                                  $publishedAt,                             //published_at
                                  $articleDate,                             //article_date
                                  $articleRevisionDate,                     //article_revision_date
                                  $currentDateStr,                          //inserted_at
                                  $currentDateStr);                         //updated_at

                  //create array of values to insert in the database
                  $toInsert = createInsertArray(TABLE_ARTICLE, $values);
                  //insert disease in the database
                  $this->connector->insertInto(TABLE_ARTICLE, $toInsert);
                  //save article id for later use
                  $dbArticleId = getLastInsertId($this->dbLink);

                  //**************tables Article_Author e Author*****************
                  $authors = $pubmedFeach->getArticleAuthors();

                  foreach($authors as $author){
                      echo ''.$author.' |';
                  }

                  //TODO
              }

          }

          //**************table Photos*****************
          //TODO

          //**************table Tweets*****************
          //TODO

          //close db connection
          $this->connector->disconnect();
      }

    }
?>
