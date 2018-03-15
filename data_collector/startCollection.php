<?php
  include_once '../data_collector/dataCollector.php';

  $numberDiseases = 2;

  $collector = new DataCollector($numberDiseases);
  $collector->getAllData();

?>
