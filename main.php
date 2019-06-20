<?php
require_once "./DmWebhook.php";

$obj = new DmWebook();

// Input mocks - to be replaced

$mockInputStr = array("hello, can i know more info?");
$mockCurrHourlyquota = 5;

foreach($obj->getAllCOnfig() as $confIndex => $config) {

  $obj->log("Config index: ".$confIndex);

  $obj->checkKeywords($config,$mockInputStr[0]);

}
