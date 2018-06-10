<?php
  include_once '../data_collector/dataCollector.php';

  $numberDiseases = 50;

  $collector = new DataCollector($numberDiseases);
  $collector->getAllData();

?>
