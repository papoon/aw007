<?php

    require_once 'controller.php';
    require_once '../models/disease.php';
    require_once '../models/articles.php';


    class Articles extends Controller{
        private $disease;
        private $article;

        public function __construct(){
            parent::__construct();

            $this->article = new Article();

        }
        public function index($id=0){
            if($id == 0){

                $articles = $this->article->getArticles();
                $this->view->message =  array('articles' => $articles);

                $this->view->render('articles/index.html.twig');
            }
            else{

                $this->article = new Article();

                $article = $this->article->getArticle($id);
                $articleMERTerms = $this->article->getMERTerms($id);              

                unset($this->article);

                $this->view->message = [];
                $this->view->message['articleMERTerms'] = $articleMERTerms;
                $this->view->message['article'] = $article;

                $this->view->render('articles/article.html.twig');
            }

        }
        
        
    }

?>
