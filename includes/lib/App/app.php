<?php

    class App{

        public function __construct(){

            //include_once '../database/dbConnector.php';
            //include_once '../database/dbUtils.php';

            require_once '../includes/lib/Twig/Autoloader.php';
            Twig_Autoloader::register();


            //$loader = new Twig_Loader_Filesystem('public_html/templates');
            //$twig = new Twig_Environment($loader);

            /*$connector = DbConnector::defaultConnection();
            $connector->connect();

            $data = $connector->selectAll(TABLE_DISEASE);
            //printDataFromTable($data, TABLE_AUTHOR);
            //var_dump(convertDatasetToArray($data));

            $connector->disconnect();

            echo $twig->render('index.html', array('diseases' => $data));

            die();*/

            //route
            $tokens = explode('/',rtrim($_SERVER['REQUEST_URI'],'/'));

            //server
            if(count($tokens)==2){
                $server = $tokens[0];
                $domain = $tokens[1];
                //$public = $tokens[2];
            }
            else if(count($tokens)==3){
                $server = $tokens[0];
                $domain = $tokens[1];
                $page = $tokens[2];
                //$page = $tokens[3];
            }
            else if(count($tokens)==4){
                $server = $tokens[0];
                $domain = $tokens[1];
                //$public = $tokens[2];
                $page = $tokens[2];
                $function = $tokens[3];
            }
            else if(count($tokens)==5){
                $server = $tokens[0];
                $domain = $tokens[1];
                //$public = $tokens[2];
                $page = $tokens[2];
                $function = $tokens[3];
                $param = $tokens[4];
            }
            else{
                require_once('../controllers/index.php');
                $controller = new Index();
                $controller->index();
                die();
            }

            if(count($tokens)>2){

                $controller = $page;
                if(file_exists('../controllers/'.$controller.'.php')){
                    require_once('../controllers/'.$controller.'.php');
                    $controller = ucfirst($controller);
                    $controller = new $controller;

                    if(isset($tokens[2])){

                        $action = $tokens[2];

                        if(isset($tokens[3])){
                            if(is_numeric($tokens[3])){
                                $controller->index($tokens[3]);
                            }
                            else{
                                $controller->{$tokens[3]}();
                            }

                        }
                        else{
                            $controller->index();
                        }

                    }
                    else{
                        $controller = ucfirst($tokens[1]);
                        $controller = new $controller;
                        $controller->index();
                    }
                }
                else{
                    require_once '../controllers/error.php';
                    $controller = 'Error';
                    $controller = new $controller;
                    $controller->index();
                }
            }
            else{
                require_once '../controllers/index.php';
                $controller = new Index();
                $controller->index();
            }

        }

    }


?>
