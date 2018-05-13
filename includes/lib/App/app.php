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

            require_once '../utils/helpers.php';
            require_once '../utils/dispatcher.php';

            // Get the client ip address
            $ip_address = getClientIP();
            if(!isset($_COOKIE['client'])) {
                setcookie('client', $ip_address,0);
                //$_COOKIE['client'] = $ip_address;
            }
            #setcookie($key,$value,time() + (86400 * $experie_days)); 
           
            //handle new client visit to site
            addNewClientOfSite();

            //criar sessao de user
            sessionHandling();


            
            //explode ?
            $route = explode('?',$_SERVER['REQUEST_URI']);
            $route = $route[0];
            //route
            $tokens = explode('/',rtrim($route,'/'));

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
            else if(count($tokens)>=5){
                $server = $tokens[0];
                $domain = $tokens[1];
                //$public = $tokens[2];
                $page = $tokens[2];
                $function = $tokens[3];
                $param = $tokens[4];
            }
            /*else{
                require_once('../controllers/index.php');
                $controller = new Index();
                $controller->index();
                die();
            }*/

            if(count($tokens)>2){

                $controller = $page;
                if(file_exists('../controllers/'.$controller.'.php')){
                    require_once('../controllers/'.$controller.'.php');
                    $controller = ucfirst($controller);
                    $controller = new $controller;

                    if(isset($tokens[2])){
                        
                        if(isset($tokens[3])){
                            if(is_numeric($tokens[3])){
                                $controller->index($function);
                            }
                            else{
                                if(count($tokens)>= 5){
                                    if(count($tokens)== 5){
                                        $controller->{$function}($param);
                                    }
                                    else{
                                        //dispatcher for url like rest/feedback/article/1 and so on..
                                        Dispatcher::route('rest@ratingArticle',$route,'rest/feedback/rating/article/{$id}');
                                        Dispatcher::route('rest@ratingDisease',$route,'rest/feedback/rating/disease/{$id}');
                                        Dispatcher::route('rest@commentArticle',$route,'rest/feedback/comment/article/{$id}');
                                        Dispatcher::route('rest@commentDisease',$route,'rest/feedback/comment/disease/{$id}');
                                        Dispatcher::route('rest@ratingDiseaseArticle',$route,'rest/feedback/rating/diseaseinarticle/{$id}');
                                    }
                                    
                                }else{
                                    $controller->{$function}();
                                } 
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
