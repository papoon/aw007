<?php
  include_once '../data_collector/dataCollector.php';

  $numberDiseases = 10;

  $collector = new DataCollector($numberDiseases);
  $collector->getAllData();

?>
