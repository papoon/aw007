<?php
    require_once 'model.php';
    class Statistic extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getAll(){


            $data = array();

            $data['articlesPerJournal'] = $this->articlesPerJournal();
            $data['tweetsPerDisease'] = $this->tweetsPerDisease();
            $data['photosPerDisease'] = $this->photosPerDisease();
            $data['articlesPerDay'] = $this->articlesPerDay();
            $data['sharesPhotosPerDisease'] = $this->sharesPhotosPerDisease();
            $data['sharesTweetsPerDisease'] = $this->sharesTweetsPerDisease();
            $data['tweetsAuthor'] = $this->tweetsAuthor();
            

            return $data;


        }
        #numero de artigos por publicados em cada jornal
        public function articlesPerJournal(){
            $result = $this->connector->rawQuery('select journal_id, count(*) as n_articles from Article
            where journal_id != "" group by journal_id order by count(*) desc limit 10;');

            
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }
        #numero de tweets por disease
        public function tweetsPerDisease(){

            $result = $this->connector->rawQuery('select a.id,a.name, b.did, count(*) as n_tweets from Disease as a inner join Tweets as b
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;');

            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }
        #numero de photos por disease desc
        public function photosPerDisease(){
            $result = $this->connector->rawQuery('select a.id,a.name , b.did,count(*) as n_photos from Disease as a inner join Photos as b
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;');

            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }
        #artigos por dia
        public function articlesPerDay(){
            $result = $this->connector->rawQuery('select id,article_date,count(*) as n_articles from Article as a
            where a.`article_date` != "0000-00-00 00:00:00"
            group by article_date order by count(*) desc limit 10;');

            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }
        # numero de shares por disease photos
        public function sharesPhotosPerDisease(){

            $result = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_photos from Disease as a inner join Photos as b
            on a.id = b.did
            group by a.id,b.did order by sum(shares)  desc limit 10;');

            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }

        public function sharesTweetsPerDisease(){
            $result = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_tweets from Disease as a inner join Tweets as b
            on a.id = b.did
            group by a.id,b.did order by sum(shares) desc limit 10;');

            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $data;
        }
        # numero tweets por author sobre as doenças
        public function tweetsAuthor(){
            $result = $this->connector->rawQuery('select b.author_name,sum(a.id) as s_tweets from Disease as a inner join Tweets as b 
            on a.id = b.did
            group by b.author_name order by sum(a.id)  desc limit 10;');

            while ($row = $result->fetch_row()) {
                array_walk_recursive($row, function(&$value) {
                    $value = utf8_encode($value);
                });
                $data[] = $row;
            }


            return $data;
        }

    }


?>
