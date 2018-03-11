<?php

    include_once "../utils/request.php";

    class DBPediaDiseases{

        private $uri = "http://dbpedia.org/sparql?query=";
        private $numberDiseases;
        private $format = "json";

        function __construct($numberDiseases=10)
        {
            $this->numberDiseases = $numberDiseases;
        }

        function getUrlDbpediaDiseasesJson($number)
        {
            //$format = 'json';

            $query =
            "PREFIX dbo: <http://dbpedia.org/ontology/>
            SELECT * where {
            ?name a  dbo:Disease .
            ?name rdfs:label ?label.
            ?name dbo:thumbnail ?thumbnail.
            ?name dbo:abstract ?abstract.
            ?name dbo:wikiPageID ?wikiPageID.
            ?name dbo:wikiPageRevisionID ?wikiPageRevisionID.
            filter(langMatches(lang(?abstract),'en')).
            filter(langMatches(lang(?label),'en')).
            } LIMIT ".$number;

            $searchUrl = $this->uri.urlencode($query).'&format='.$this->format;

            return $searchUrl;
        }

        function getResponseDiseasesJson(){

            $response =  json_decode(getResponseCurl($this->getUrlDbpediaDiseasesJson($this->numberDiseases)),true);

            return $response;
        }

        function getDiseases(){

            $responseDiseasesJson = $this->getResponseDiseasesJson();

            return $responseDiseasesJson['results']['bindings'];
        }



    }
?>
