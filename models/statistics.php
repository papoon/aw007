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
            $data = $this->connector->rawQuery('select journal_id, count(*) as n_articles from article 
            where journal_id != "" group by journal_id order by count(*) desc limit 10;')->fetch_all();

            return $data;
        }
        #numero de tweets por disease
        public function tweetsPerDisease(){

            $data = $this->connector->rawQuery('select a.id,a.name, b.did, count(*) as n_tweets from disease as a inner join tweets as b 
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;')->fetch_all();

            return $data;
        }
        #numero de photospor disease desc
        public function photosPerDisease(){
            $data = $this->connector->rawQuery('select a.id,a.name , b.did,count(*) as n_photos from disease as a inner join photos as b 
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;')->fetch_all();

            return $data;
        }
        #artigos por dia
        public function articlesPerDay(){
            $data = $this->connector->rawQuery('select id,article_date,count(*) as n_articles from article as a 
            where a.`article_date` != "0000-00-00 00:00:00"
            group by article_date order by count(*) desc limit 10;')->fetch_all();

            return $data;
        }
        # numero de shares por disease photos
        public function sharesPhotosPerDisease(){

            $data = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_photos from disease as a inner join photos as b 
            on a.id = b.did
            group by a.id,b.did order by sum(shares)  desc limit 10;')->fetch_all();

            return $data;
        }

        public function sharesTweetsPerDisease(){
            $data = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_tweets from disease as a inner join tweets as b 
            on a.id = b.did
            group by a.id,b.did order by sum(shares) desc limit 10;')->fetch_all();

            return $data;
        }
        # numero tweets por author sobre as doenças
        public function tweetsAuthor(){
            $data = $this->connector->rawQuery('select b.author_name,sum(a.id) as s_tweets from disease as a inner join tweets as b 
            on a.id = b.did
            group by b.author_name order by sum(a.id)  desc limit 10;')->fetch_all();

            return $data;
        }

    }


?>