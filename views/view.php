<?php

    class View{

        public $message;
        private $twig;
        public function __construct(){

            $loader = new Twig_Loader_Filesystem('templates');
            $this->twig = new Twig_Environment($loader);
            $this->twig->addGlobal('session', $_SESSION);
        
        }
        public function render($view){
            echo $this->twig->render($view, $this->message);
        }

    }

?>






