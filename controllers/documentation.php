<?php

    require_once 'controller.php';

    class Documentation extends Controller{
        private $documentation;

        public function __construct(){
            parent::__construct();
        }

        public function index(){

            $this->view->message =  array('doc' => 'Documentation');
            $this->view->render('documentation/index.html.twig');
        }

    }

?>
