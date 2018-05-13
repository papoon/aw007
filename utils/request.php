<?php

    function getResponseCurlNoCache($url,$header=""){

        // is curl installed?
        if (!function_exists('curl_init')){
            die('CURL is not installed!');
        }

        // get curl handle
        $ch= curl_init();

        // set request url
        curl_setopt($ch, CURLOPT_URL, $url);
        //header
        if($header!="")
        {
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        }
        //https
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        // return response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $err = curl_errno($ch);

        curl_close($ch);

        return $response;
    }

    function getResponseCurl($url,$header=""){
        $cacheFolder = 'cache/'.hash('sha256', $url);

        if (!file_exists($cacheFolder)) {
            mkdir($cacheFolder, 0777, true);
        }

        $cacheFile = $cacheFolder.'/content';

        if (file_exists($cacheFile)) {
          $response = file_get_contents($cacheFile);
        } else {
          $response = getResponseCurlNoCache($url, $header);
          file_put_contents($cacheFile, $response);
        }

        return $response;
    }

?>
