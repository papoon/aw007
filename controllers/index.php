<?php
    require_once 'controller.php';

    class Index extends Controller{
        public function __construct(){
            parent::__construct();
        }
        public function index(){
            $this->view->message =  array('message' => 'Hello Wolrd from Index Controler Twig');

            #$twig->render('index.html', array('diseases' => $data));
            $this->view->render('index/index.html');
           
        }
    }


?>