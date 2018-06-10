<?php
    require_once '../database/dbConnector.php';
    require_once '../database/dbUtils.php';

    function buildBaseString($baseURI, $method, $params)
    {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    function buildAuthorizationHeader($oauth)
    {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    function getOneWeekAgoTimestamp() {

      //get unix timestamp
      $now = strtotime("now");
      //build new datetime object with it
      $date = new DateTime();
      $date->setTimestamp($now);
      //subtract 7 days
      $date->modify('-7 day');
      //reset the time
      $date->setTime(0,0,0);

      return $date->getTimestamp();
    }

    function getOneYearAgoTimestamp() {

      //get unix timestamp
      $now = strtotime("now");
      //build new datetime object with it
      $date = new DateTime();
      $date->setTimestamp($now);
      //subtract 1 year
      $date->modify('-1 year');
      //reset the time
      $date->setTime(0,0,0);

      return $date->getTimestamp();
    }

    function getStringForUrl($dataStr) {
      return str_replace(' ', '%20', $dataStr);
    }
    // Function to get the client ip address
    function getClientIP() {

        $ipaddress = '';
        
        if($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
    
        return $ipaddress;
    }
    function addNewClientOfSite(){

        #VERIFICAMOS DE JÁ EXSITE COOCKIE COMO FORMA DE 
        #NÃO ESTAR SEMPRE A VERIFICAR SE É NOVO VISTANTE QUNDO O UTILIZADOR USA O SITE
        $ip_address = getClientIP();
        if(!isset($_COOKIE['client']) || $_COOKIE['client'] != $ip_address) {
            

            $connector = DbConnector::defaultConnection();
            $connector->connect();

            
            //check if ip already exits
            $exists_ip = $connector->selectWhere(TABLE_CLIENTS_SITE,'ip_address','=',$ip_address,'char')->fetch_row();

            if(count($exists_ip) == 0){

                $currentDate = new DateTime();
                $currentDateStr = $currentDate->format('Y-m-d H:i:s');

                $values = array('UNKNOWN',
                                $ip_address,
                                $currentDateStr
                            );  

                $toInsert = createInsertArray(TABLE_CLIENTS_SITE, $values);
                $connector->insertInto(TABLE_CLIENTS_SITE, $toInsert);
            }
            
            $connector->disconnect();

        }

        

    }

    function sessionHandling(){

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 5 minutes - 300 seconds
        $inactive = 300; 
        ini_set('session.gc_maxlifetime', $inactive); 


        if (isset($_SESSION['expire']) && (time() - $_SESSION['expire'] > $inactive)) {
            session_unset();     
            session_destroy();   
        }
        
        $_SESSION['expire'] = time(); 
        
        if(!isset($_SESSION['user'])) {

            $connector = DbConnector::defaultConnection();
            $connector->connect();

            $ip_address = getClientIP();

            $user = $connector->selectWhere(TABLE_CLIENTS_SITE,'ip_address','=',$ip_address,'char')->fetch_assoc();



            $_SESSION['user'] = $user;

            $connector->disconnect();
        }
    }

?>
