<?php

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

    function getStringForUrl($dataStr) {
      return str_replace(' ', '%20', $dataStr);
    }
?>
