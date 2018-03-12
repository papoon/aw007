<?php
  include_once '../data_collector/dataCollector.php';

  $numberDiseases = 2;
  $numberElements = 5;

  $collector = new DataCollector($numberDiseases, $numberElements);
  $collector->startCollectionAll();

?>
