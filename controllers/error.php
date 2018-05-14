<?php
    require_once 'controller.php';
    class ErrorController extends Controller{

        public function index(){
            $this->view->message =  array('error' => 'Page Not Found');

            #$twig->render('index.html', array('diseases' => $data));
            $this->view->render('error.html');
        }
    }

?>
