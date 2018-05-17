<?php
    require_once 'model.php';
    class Statistic extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getAll(){

            $data = array();

            $data['articlesPerDisease'] = $this->articlesPerDisease();
            $data['tweetsPerDisease'] = $this->tweetsPerDisease();
            $data['photosPerDisease'] = $this->photosPerDisease();
            $data['sharesPhotosPerDisease'] = $this->sharesPhotosPerDisease();
            $data['sharesTweetsPerDisease'] = $this->sharesTweetsPerDisease();
            $data['articlesPerDay'] = $this->articlesPerDay();
            $data['articlesPerJournal'] = $this->articlesPerJournal();
            $data['tweetsAuthor'] = $this->tweetsAuthor();
            $data['mostFreqMERTermsArticles'] = $this->mostFreqMERTermsArticles();
            $data['mostFreqMERTermsTweets'] = $this->mostFreqMERTermsTweets();
            $data['maxTfidfArticles'] = $this->maxTfidfArticles();
            $data['maxTfidfTweets'] = $this->maxTfidfTweets();
            $data['maxResnikArticles'] = $this->maxResnikArticles();
            $data['maxResnikTweets'] = $this->maxResnikTweets();

            return $data;

        }

        #termos mais frequentes encontrados pelo MER em Artigos
        public function mostFreqMERTermsArticles(){

            $result = $this->connector->rawQuery('select term, count(*) from MER_Terms_Articles
            group by term order by count(*) desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #termos mais frequentes encontrados pelo MER em Tweets
        public function mostFreqMERTermsTweets(){

            $result = $this->connector->rawQuery('select term, count(*) from MER_Terms_Tweets
            group by term order by count(*) desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #valores de TfIdf mais altos de termos encontrados em artigos
        public function maxTfidfArticles(){

            $result = $this->connector->rawQuery('select term, article_id, MAX(tf_idf_value) from Tf_Idf_Articles
            group by term order by MAX(tf_idf_value) desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #valores de TfIdf mais altos de termos encontrados em Tweets
        public function maxTfidfTweets(){

            $result = $this->connector->rawQuery('select term, tweet_id, MAX(tf_idf_value) from Tf_Idf_Tweets
            group by term order by MAX(tf_idf_value) desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #valores de Semelhanca Resnik mais altos de termos encontrados em artigos
        public function maxResnikArticles(){

            $result = $this->connector->rawQuery('select did, article_id, resnik_value from Similarity_Articles
            order by resnik_value desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #valores de Semelhanca Resnik mais altos de termos encontrados em tweets
        public function maxResnikTweets(){

            $result = $this->connector->rawQuery('select did, tweet_id, resnik_value from Similarity_Tweets
            order by resnik_value desc
            limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #numero de artigos por disease
        public function articlesPerDisease(){

            $result = $this->connector->rawQuery('select a.id,a.name, b.did, count(*) as n_articles from Disease as a inner join Article as b
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        #numero de artigos por publicados em cada jornal
        public function articlesPerJournal(){
            $result = $this->connector->rawQuery('select journal_id, count(*) as n_articles from Article
            where journal_id != "" group by journal_id order by count(*) desc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
        #numero de tweets por disease
        public function tweetsPerDisease(){

            $result = $this->connector->rawQuery('select a.id,a.name, b.did, count(*) as n_tweets from Disease as a inner join Tweets as b
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
        #numero de photos por disease desc
        public function photosPerDisease(){
            $result = $this->connector->rawQuery('select a.id,a.name , b.did,count(*) as n_photos from Disease as a inner join Photos as b
            on a.id = b.did
            group by a.id,b.did order by count(*)  asc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
        #artigos por dia
        public function articlesPerDay(){
            $result = $this->connector->rawQuery('select id,article_date,count(*) as n_articles from Article as a
            where a.`article_date` != "0000-00-00 00:00:00"
            group by article_date order by count(*) desc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
        # numero de shares por disease photos
        public function sharesPhotosPerDisease(){

            $result = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_photos from Disease as a inner join Photos as b
            on a.id = b.did
            group by a.id,b.did order by sum(shares)  desc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }

        public function sharesTweetsPerDisease(){
            $result = $this->connector->rawQuery('select a.id,a.name, b.did,sum(shares) as s_shares_tweets from Disease as a inner join Tweets as b
            on a.id = b.did
            group by a.id,b.did order by sum(shares) desc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
        # numero tweets por author sobre as doenças
        public function tweetsAuthor(){
            $result = $this->connector->rawQuery('select b.author_name,sum(a.id) as s_tweets from Disease as a inner join Tweets as b
            on a.id = b.did
            group by b.author_name order by sum(a.id)  desc limit 10;');

            $data = array();
            while ($row = $result->fetch_row()) {
                array_walk_recursive($row, function(&$value) {
                    $value = utf8_encode($value);
                });
                $data[] = $row;
            }


            return $this->utf8magic($data);
        }

    }


?>
