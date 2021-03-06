<?php

    //include_once "utils/dbpedia.php";
    //include_once "utils/request.php";
    include_once "../webservices/dbpediaDiseases.php";
    include_once "../webservices/pubmedSearch.php";
    include_once "../webservices/pubmedFeach.php";
    include_once "../webservices/flickr.php";
    include_once "../webservices/flickrPhotoInfo.php";
    include_once "../webservices/twitterSearch.php";
    include_once "../webservices/twitterEmbed.php";
    //include_once "../utils/database.php";

    echo "Hello World";

    $dbPediaDiseases = new DBPediaDiseases(5);

    //$response = $dbPediaDiseases->getResponseDiseasesJson();
    //var_dump($response);
    //die();
    /*
        diseases
        <id:></id:>
        <name></name>
        <dbpedia_id></dbpedia_id>
        <dbpedia_revision_id></dbpedia_revision_id>
        <abstract></abstract>
        <thumbnail></thumbnail>
        <uri></uri>
        <created_at></created_at>
        <update_at></update_at>

    */

    $diseases = $dbPediaDiseases->getDiseases();
    /*var_dump($diseases[0]['label']['value']);//name
    var_dump($diseases[0]['wikiPageID']['value']);//dbpedia_id
    var_dump($diseases[0]['wikiPageRevisionID']['value']);//dbpedia_revision_id
    var_dump($diseases[0]['abstract']['value']);//abstract
    var_dump($diseases[0]['thumbnail']['value']);//thumbnail
    var_dump($diseases[0]['name']['value']);//uri*/
    //created_at automatic insert timestamp
    //update_at automatic insert timestamp

    //die();
    //obter o url para fazer o pedido http
    //$requestURL = getUrlDbpediaDiseasesJson();

    //usar o curl para fazer o pedido e retornar a resposta

    //$response =  json_decode(getResponseCurl($requestURL),true);


    /*echo '<h1>Diseases</h1>';

    //echo $diseases[0]['wikiPageID'];
    foreach($diseases as $disease){
        echo '<p>'.'<b>Id</b>: '.$disease['wikiPageID']['value'].'</p>';
        echo '<p>'.'<b>Label</b>: '.$disease['label']['value'].'</p>';
        echo '<p> <b>Abstract</b>: '.$disease['abstract']['value'].'</p>';
        echo '<hr>';
    }*/

    $label = $diseases[4]['label']['value'];

    echo '<h1>PubMed Search</h1>';

    $pubmed = new PubMedSearch($label,10);

    $ids = $pubmed->getIdLists();
    $response = $pubmed->getIdListsByDate("2015-01-01");
    //var_dump($response);

    echo '<p> <b>Label</b>: '.$label.'</p>';
    echo '<p> <b>PubMed Id</b>: '.$ids['Id'][7].'</p>';

    echo '<hr>';
    echo '<h1>PubMed Feach Disease</h1>';
    echo '<b>Label</b> :'.$label;

    $pubmedFeach = new PubMedFeach($ids['Id'][9]);
    $article = $pubmedFeach->getResponse();

    //var_dump( $article['PubmedArticle']['MedlineCitation']['Article']['AuthorList']['Author']);
    //die();
    //$article = $article['PubmedArticle']['MedlineCitation']['Article']['Abstract'];
    //var_dump($article['PubmedArticle']['PubmedData']['History']['PubMedPubDate']);
    //var_dump($article);
    //die();

    echo '<p> <b>Titulo Artigo: </b>'.$pubmedFeach->getArticleTitle().'</p>';
    try{
        echo '<p> <b>Abstract: </b>'.$pubmedFeach->getArticleAbstract().'</p>';
    }catch(Exception $e){
        echo $e->getMessage();
    }

    //$dataArtigo = $article['Journal']['JournalIssue']['PubDate'];
    echo '<p> <b>Data do artigo: </b>'.$pubmedFeach->getArticleDate().'</p>';
    echo '<p> <b>Data de publicação do artigo: </b>'.$pubmedFeach->getArticleJournalPubDate().'</p>';
    echo '<p> <b>Data de revisão do artigo: </b>'.$pubmedFeach->getArticleRevisionDate().'</p>';
    echo '<p> <b>Jornal Id: </b>'.$pubmedFeach->getArticleJournalId().'</p>';

     /*
        articles
        <did:></did:>
        <journal_id></journal_id>
        <title></title>
        <abstract></abstract>
        <published_at></published_at>
        <article_date></article_date>
        <article_revision_date></article_revision_date>
        <inserted_at></inserted_at>
        <updated_at></updated_at>

    */


    $authors = $pubmedFeach->getArticleAuthors();
    echo '<p> <b>Autores do artigo: </b>';
    foreach($authors as $author){
        echo ''.$author.' |';
    }
    echo '</p>';

    echo '<hr>';
    echo '<h2>Photos Disease</h2>'.$label.'';

    $flickr = new Flickr($label,10);
    //$photos = $flickr->getResponse();
   

    $photos = $flickr->getPhotos();

    foreach($photos as $key=>$photo){
        echo '<p>Photo ID: '.$key.'</p>';
        echo '<p>Photo URL: '.$photo['url'].'</p>';
        echo '<p>Image:<br> <img src="'.$photo['url'].'" alt="..." width="300" height="200"></br></p>';
        
        //$photo = $flickr->getPhotoInfo($key);

        //$flickrPhotoInfo = new FlickrPhotoInfo($key);

        echo '<p>AuthorName: '.$photo['authorName'].'</p>';
        echo '<p>UserName: '.$photo['username'].'</p>';
        echo '<p>NumberOfLikes: '.$photo['numerOfLikes'].'</p>';
        echo '<p>NumberOfComments: '.$photo['numberOfComments'].'</p>';
        echo '<p>NumberOfViews: '.$photo['views'].'</p>';
        echo '<p>UserLocation: '.$photo['location'].'</p>';
        echo '<p>PublishedAt: '.$photo['publishedAt'].'</p>';
        echo '<hr>';

    }
    
    die();
    

    echo '<hr>';
    echo '<h2>Twitter Disease </h2>'.$label.'';


    $twitter = new TwitterSearch($label,2);
    
    $tweets = $twitter->getTweets();
    

    //echo $twitter->getTweetId($tweets[1]);
    $tweetId = $twitter->getTweetId($tweets[0]);
    $twitter_embed = new TwitterEmbed($tweetId);
    //var_dump($twitter_embed->getResponse());
    $twiiter_embed = $twitter_embed->getResponse();
    $html = $twiiter_embed['html'];

    echo $html;

    echo '<p>Tweet URL: <a href="'.$twiiter_embed['url'].'">'.$twiiter_embed['url'].'</a> </p>';
    //echo '<p>Type '..'</p>';
    echo '<p>Tweet ID: '.$tweetId.'</p>';
    echo '<p>Author Name '.$twitter->getAuthorName($tweets[0]).'</p>';
    echo '<p>Username '.$twitter->getUsername($tweets[0]).'</p>';
    echo '<p>nr_likes '.$twitter->getNumberOfLikes($tweets[0]).'</p>';
    echo '<p>nr_comments '.$twitter->getNumberOfComments($tweets[0]).'</p>';
    echo '<p>shares '.$twitter->getNumberOfShares($tweets[0]).'</p> ';
    echo '<p>Location '.$twitter->getAuthorLocation($tweets[0]).'</p>';
    echo '<p>Published At '.$twitter->getPublishedDate($tweets[0]).'</p>';
    //echo '<p>Inserted At </p>';
    //echo '<p>Updated At </p>';

    /*echo '<br>';

    $tweet = $twitter->searchTweetId($tweetId);
    $tweetId = $twitter->getTweetId($tweet);
    $twitter_embed = new TwitterEmbed($tweetId);
    //var_dump($twitter_embed->getResponse());
    $twiiter_embed = $twitter_embed->getResponse();
    $html = $twiiter_embed['html'];

    echo $html;*/


    //var_dump($response);


?>