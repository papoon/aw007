<?php

    function getUrlDbpediaDiseasesJson($number=10)
    {
        $format = 'json';

        $query = 
        "PREFIX dbo: <http://dbpedia.org/ontology/>
         SELECT * where {
           ?name a  dbo:Disease .
           ?name rdfs:label ?label.
           ?name dbo:thumbnail ?thumbnail.
           ?name dbo:abstract ?abstract.
           ?name dbo:wikiPageID ?wikiPageID.
           ?name dbo:wikiPageRevisionID ?wikiPageRevisionID.
           filter(langMatches(lang(?abstract),'en'))
           fILTER (langMatches(lang(?label),'en'))
       } LIMIT ".$number;
        
        $searchUrl = 'http://dbpedia.org/sparql?'.'query='.urlencode($query).'&format='.$format;

        return $searchUrl;
    }

?>