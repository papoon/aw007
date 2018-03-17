<?php

    class View{

        public $message;
        private $twig;
        public function __construct(){

            $loader = new Twig_Loader_Filesystem('templates');
            $this->twig = new Twig_Environment($loader);
        
        }
        public function render($view){
            echo $this->twig->render($view, $this->message);
        }

    }

?>






