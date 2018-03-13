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

      switch ($tableName) {
        case TABLE_ARTICLE:
          $result[] = array("val"=>$valuesArray[0], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[1], "type"=>"int");   //journal_id
          $result[] = array("val"=>$valuesArray[2], "type"=>"char");  //title
          $result[] = array("val"=>$valuesArray[3], "type"=>"char");  //abstract
          $result[] = array("val"=>$valuesArray[4], "type"=>"char");  //article_date
          $result[] = array("val"=>$valuesArray[5], "type"=>"char");  //article_revision_date
          $result[] = array("val"=>$valuesArray[6], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[7], "type"=>"char");  //updated_at
          break;
        case TABLE_ARTICLE_AUTHOR:
          $result[] = array("val"=>$valuesArray[0], "type"=>"int");   //art_id
          $result[] = array("val"=>$valuesArray[1], "type"=>"int");   //aut_id
          break;
        case TABLE_AUTHOR:
          $result[] = array("val"=>$valuesArray[0], "type"=>"char");  //name
          $result[] = array("val"=>$valuesArray[1], "type"=>"char");  //institution
          $result[] = array("val"=>$valuesArray[2], "type"=>"char");  //contact
          $result[] = array("val"=>$valuesArray[3], "type"=>"char");  //inserted_at
          $result[] = array("val"=>$valuesArray[4], "type"=>"char");  //updated_at
          break;
        case TABLE_DISEASE:
          $result[] = array("val"=>$valuesArray[0], "type"=>"char");  //name
          $result[] = array("val"=>$valuesArray[1], "type"=>"int");   //dbpedia_id
          $result[] = array("val"=>$valuesArray[2], "type"=>"int");   //dbpedia_revision_id
          $result[] = array("val"=>$valuesArray[3], "type"=>"char");  //abstract
          $result[] = array("val"=>$valuesArray[4], "type"=>"char");  //thumbnail
          $result[] = array("val"=>$valuesArray[5], "type"=>"char");  //uri
          $result[] = array("val"=>$valuesArray[6], "type"=>"char");  //created_at
          $result[] = array("val"=>$valuesArray[7], "type"=>"char");  //updated_at
          break;
        case TABLE_PHOTOS:
          $result[] = array("val"=>$valuesArray[0], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[1], "type"=>"char");  //url
          $result[] = array("val"=>$valuesArray[2], "type"=>"char");  //flicrk_id
          $result[] = array("val"=>$valuesArray[3], "type"=>"char");  //author_name
          $result[] = array("val"=>$valuesArray[4], "type"=>"char");  //username
          $result[] = array("val"=>$valuesArray[5], "type"=>"int");   //nr_likes
          $result[] = array("val"=>$valuesArray[6], "type"=>"int");   //nr_comments
          $result[] = array("val"=>$valuesArray[7], "type"=>"int");   //shares
          $result[] = array("val"=>$valuesArray[8], "type"=>"char");  //country
          $result[] = array("val"=>$valuesArray[9], "type"=>"char");  //published_at
          $result[] = array("val"=>$valuesArray[10], "type"=>"char"); //inserted_at
          $result[] = array("val"=>$valuesArray[11], "type"=>"char"); //updated_at
          break;
        case TABLE_TWEETS:
          $result[] = array("val"=>$valuesArray[0], "type"=>"int");   //did
          $result[] = array("val"=>$valuesArray[1], "type"=>"char");  //url
          $result[] = array("val"=>$valuesArray[2], "type"=>"char");  //type
          $result[] = array("val"=>$valuesArray[3], "type"=>"char");  //tweet_id
          $result[] = array("val"=>$valuesArray[4], "type"=>"char");  //author_name
          $result[] = array("val"=>$valuesArray[5], "type"=>"char");  //username
          $result[] = array("val"=>$valuesArray[6], "type"=>"int");   //nr_likes
          $result[] = array("val"=>$valuesArray[7], "type"=>"int");   //nr_comments
          $result[] = array("val"=>$valuesArray[8], "type"=>"int");   //shares
          $result[] = array("val"=>$valuesArray[9], "type"=>"char");  //country
          $result[] = array("val"=>$valuesArray[10], "type"=>"char");  //published_at
          $result[] = array("val"=>$valuesArray[11], "type"=>"char"); //inserted_at
          $result[] = array("val"=>$valuesArray[12], "type"=>"char"); //updated_at
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
          $valuesStr .= 'journal_id,';
          $valuesStr .= 'title,';
          $valuesStr .= 'abstract,';
          $valuesStr .= 'article_date,';
          $valuesStr .= 'article_revision_date,';
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
          $valuesStr .= 'created_at,';
          $valuesStr .= 'updated_at';
          break;
        case TABLE_PHOTOS:
          $valuesStr .= 'did,';
          $valuesStr .= 'url,';
          $valuesStr .= 'flicrk_id,';
          $valuesStr .= 'author_name,';
          $valuesStr .= 'username';
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
          $valuesStr .= 'username';
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
            echo '<p>'.$rows['journal_id'].'</p>';
            echo '<p>'.$rows['title'].'</p>';
            echo '<p>'.$rows['abstract'].'</p>';
            echo '<p>'.$rows['article_date'].'</p>';
            echo '<p>'.$rows['article_revision_date'].'</p>';
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
