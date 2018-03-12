<?php

    const TABLE_ARTICLE = 'Article';
    const TABLE_ARTICLE_AUTHOR = 'Article_Author';
    const TABLE_AUTHOR = 'Author';
    const TABLE_DISEASE = 'Disease';
    const TABLE_PHOTOS = 'Photos';
    const TABLE_TWEETS = 'Tweets';

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
            echo '<p>'.$rows['abstract'].'</p>';
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

    function printCountResult($data) {
      $row = $data->fetch_row();
      echo '<p>'.$row[0].'</p>';
    }

    function getCountResult($data) {
      $row = $data->fetch_row();
      return $row[0];
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
          $valuesStr .= 'abstract,';
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
?>
