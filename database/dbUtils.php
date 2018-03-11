<?php

    function printDataFromTable($data, $tableName)
    {
      switch ($tableName) {
        case 'Article':
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
        case 'Article_Author':
          while ( $rows = $data->fetch_assoc() ) {
            echo '<p>'.$rows['art_id'].'</p>';
            echo '<p>'.$rows['aut_id'].'</p>';
            echo '<br/>';
          }
          break;
        case 'Author':
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
        case 'Disease':
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
        case 'Photos':
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
        case 'Tweets':
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

    function getValuesStr($tableName) {

      $valuesStr = ' (';

      switch ($tableName) {
        case 'Article':
          $valuesStr .= 'did,';
          $valuesStr .= 'journal_id,';
          $valuesStr .= 'title,';
          $valuesStr .= 'abstract,';
          $valuesStr .= 'article_date,';
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        case 'Article_Author':
          $valuesStr .= 'art_id,';
          $valuesStr .= 'aut_id';
          break;
        case 'Author':
          $valuesStr .= 'name,';
          $valuesStr .= 'institution,';
          $valuesStr .= 'contact,';
          $valuesStr .= 'inserted_at,';
          $valuesStr .= 'updated_at';
          break;
        case 'Disease':
          $valuesStr .= 'name,';
          $valuesStr .= 'dbpedia_id,';
          $valuesStr .= 'abstract,';
          $valuesStr .= 'created_at,';
          $valuesStr .= 'updated_at';
          break;
        case 'Photos':
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
        case 'Tweets':
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
