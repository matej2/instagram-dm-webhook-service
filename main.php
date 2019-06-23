<?php
require_once "./DmWebhook.php";

$obj = new DmWebook();

// Input mocks - to be replaced

$mockInputStr = array("hello, can i know more info?");
$mockCurrHourlyquota = 5;

var_dump($obj->getLastDM());

foreach($obj->getWebhooks() as $whIndex => $wh) {

  $obj->log("Config index: ".$whIndex);

  $obj->checkKeywords($wh,$mockInputStr[0]);

}
