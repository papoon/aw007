<?php
include_once '../data_collector/dataCollector.php';

$diseaseId = 1;

$collector = new DataCollector();
$collector->updateAllData();

#$collector->updateDiseaseById($diseaseId);

?>
