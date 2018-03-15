<?php

    const TABLE_ARTICLE = 'Article';
    const TABLE_ARTICLE_AUTHOR = 'Article_Author';
    const TABLE_AUTHOR = 'Author';
    const TABLE_DISEASE = 'Disease';
    const TABLE_PHOTOS = 'Photos';
    const TABLE_TWEETS = 'Tweets';

    function getLastInsertId($connection) {
      return mysqli_insert_id($connection);
    }

    function printCountResult($data) {
      $row = $data->fetch_row();
      echo '<p>'.$row[0].'</p>';
    }

    function getCountResult($data) {
      $row = $data->fetch_row();
      return $row[0];
    }

    function getNumberRows($data) {
      return mysqli_num_rows($data);
    }

    function printColumn($data, $columnName) {
      while ( $rows = $data->fetch_assoc() ) {
        echo '<p>'.$rows[$columnName].'</p>';
        echo '<br/>';
      }
    }

    function convertDatasetToArray($data) {
      $rows = [];
      while($row = $data->fetch_assoc()) {
        $rows[] = $row;
      }
      return $rows;
    }

    function createInsertArray($tableName, $valuesArray) {

      $result = array();
      $i = 0;

      switch ($tableName) {
        case TABLE_ARTICLE:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //article_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //journal_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //title
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //abstract
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //published_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //article_date
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //article_revision_date
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //authors (TEMP)
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //updated_at
          break;
        case TABLE_ARTICLE_AUTHOR:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //art_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //aut_id
          break;
        case TABLE_AUTHOR:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //name
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //institution
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //contact
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //updated_at
          break;
        case TABLE_DISEASE:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //name
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //dbpedia_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //dbpedia_revision_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //abstract
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //thumbnail
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //uri
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //created_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //updated_at
          break;
        case TABLE_PHOTOS:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //url
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //flicrk_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //author_name
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //username
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //nr_likes
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //nr_comments
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //shares
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //country
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //published_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //updated_at
          break;
        case TABLE_TWEETS:
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //url
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //type
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //tweet_id
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //author_name
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //username
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //nr_likes
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //nr_comments
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"int");   //shares
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //country
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //published_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[$i++], "type"=>"char");  //updated_at
          break;
        //case 'Comments':
        //  # code...
        //  break;
        default:
          # code...
          break;
        }

        return $result;
    }

    function getValuesStr($tableName) {

      $valuesStr = ' (';

      switch ($tableName) {
        case TABLE_ARTICLE:
          $valuesStr .= 'did,';
          $valuesStr .= 'article_id,';
          $valuesStr .= 'journal_id,';
          $valuesStr .= 'title,';
          $valuesStr .= 'abstract,';
          $valuesStr .= 'published_at,';
          $valuesStr .= 'article_date,';
          $valuesStr .= 'article_revision_date,';
          $valuesStr .= 'authors,';  //(TEMP)
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        case TABLE_ARTICLE_AUTHOR:
          $valuesStr .= 'art_id,';
          $valuesStr .= 'aut_id';
          break;
        case TABLE_AUTHOR:
          $valuesStr .= 'name,';
          $valuesStr .= 'institution,';
          $valuesStr .= 'contact,';
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        case TABLE_DISEASE:
          $valuesStr .= 'name,';
          $valuesStr .= 'dbpedia_id,';
          $valuesStr .= 'dbpedia_revision_id,';
          $valuesStr .= 'abstract,';
          $valuesStr .= 'thumbnail,';
          $valuesStr .= 'uri,';
          $valuesStr .= 'created_at,';
          $valuesStr .= 'updated_at';
          break;
        case TABLE_PHOTOS:
          $valuesStr .= 'did,';
          $valuesStr .= 'url,';
          $valuesStr .= 'flicrk_id,';
          $valuesStr .= 'author_name,';
          $valuesStr .= 'username,';
          $valuesStr .= 'nr_likes,';
          $valuesStr .= 'nr_comments,';
          $valuesStr .= 'shares,';
          $valuesStr .= 'country,';
          $valuesStr .= 'published_at,';
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        case TABLE_TWEETS:
          $valuesStr .= 'did,';
          $valuesStr .= 'url,';
          $valuesStr .= 'type,';
          $valuesStr .= 'tweet_id,';
          $valuesStr .= 'author_name,';
          $valuesStr .= 'username,';
          $valuesStr .= 'nr_likes,';
          $valuesStr .= 'nr_comments,';
          $valuesStr .= 'shares,';
          $valuesStr .= 'country,';
          $valuesStr .= 'published_at,';
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        //case 'Comments':
        //  # code...
        //  break;
        default:
          # code...
          break;
        }

        $valuesStr .= ') ';

        return $valuesStr;
    }

    function printDataFromTable($data, $tableName)
    {
      switch ($tableName) {
        case TABLE_ARTICLE:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['id'].'</p>';
            echo '<p>'.$rows['did'].'</p>';
            echo '<p>'.$rows['article_id'].'</p>';
            echo '<p>'.$rows['journal_id'].'</p>';
            echo '<p>'.$rows['title'].'</p>';
            echo '<p>'.$rows['abstract'].'</p>';
            echo '<p>'.$rows['published_at'].'</p>';
            echo '<p>'.$rows['article_date'].'</p>';
            echo '<p>'.$rows['article_revision_date'].'</p>';
            echo '<p>'.$rows['authors'].'</p>';
            echo '<p>'.$rows['inserted_at'].'</p>';
            echo '<p>'.$rows['updated_at'].'</p>';
            echo '<br/>';
          }
          break;
        case TABLE_ARTICLE_AUTHOR:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['art_id'].'</p>';
            echo '<p>'.$rows['aut_id'].'</p>';
            echo '<br/>';
          }
          break;
        case TABLE_AUTHOR:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['id'].'</p>';
            echo '<p>'.$rows['name'].'</p>';
            echo '<p>'.$rows['institution'].'</p>';
            echo '<p>'.$rows['contact'].'</p>';
            echo '<p>'.$rows['inserted_at'].'</p>';
            echo '<p>'.$rows['updated_at'].'</p>';
            echo '<br/>';
          }
          break;
        case TABLE_DISEASE:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['id'].'</p>';
            echo '<p>'.$rows['name'].'</p>';
            echo '<p>'.$rows['dbpedia_id'].'</p>';
            echo '<p>'.$rows['dbpedia_revision_id'].'</p>';
            echo '<p>'.$rows['abstract'].'</p>';
            echo '<p>'.$rows['thumbnail'].'</p>';
            echo '<p>'.$rows['uri'].'</p>';
            echo '<p>'.$rows['created_at'].'</p>';
            echo '<p>'.$rows['updated_at'].'</p>';
            echo '<br/>';
          }
          break;
        case TABLE_PHOTOS:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['id'].'</p>';
            echo '<p>'.$rows['did'].'</p>';
            echo '<p>'.$rows['url'].'</p>';
            echo '<p>'.$rows['flicrk_id'].'</p>';
            echo '<p>'.$rows['author_name'].'</p>';
            echo '<p>'.$rows['username'].'</p>';
            echo '<p>'.$rows['nr_likes'].'</p>';
            echo '<p>'.$rows['nr_comments'].'</p>';
            echo '<p>'.$rows['shares'].'</p>';
            echo '<p>'.$rows['country'].'</p>';
            echo '<p>'.$rows['published_at'].'</p>';
            echo '<p>'.$rows['inserted_at'].'</p>';
            echo '<p>'.$rows['updated_at'].'</p>';
            echo '<br/>';
          }
          break;
        case TABLE_TWEETS:
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['id'].'</p>';
            echo '<p>'.$rows['did'].'</p>';
            echo '<p>'.$rows['url'].'</p>';
            echo '<p>'.$rows['type'].'</p>';
            echo '<p>'.$rows['tweet_id'].'</p>';
            echo '<p>'.$rows['author_name'].'</p>';
            echo '<p>'.$rows['username'].'</p>';
            echo '<p>'.$rows['nr_likes'].'</p>';
            echo '<p>'.$rows['nr_comments'].'</p>';
            echo '<p>'.$rows['shares'].'</p>';
            echo '<p>'.$rows['country'].'</p>';
            echo '<p>'.$rows['published_at'].'</p>';
            echo '<p>'.$rows['inserted_at'].'</p>';
            echo '<p>'.$rows['updated_at'].'</p>';
            echo '<br/>';
          }
          break;
        //case 'Comments':
        //  # code...
        //  break;
        default:
          # code...
          break;
      }
    }
?>
