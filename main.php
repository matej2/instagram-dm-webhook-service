<?php
require_once './DmWebhook.php';

$obj = new DmWebook();
$mockInputStr = "hello";

foreach($obj->getAllCOnfig() as $config) {
  //if(isset($config->keywords) && strpos($config->keywords, $mockInputStr)) {
  if(strpos($config->keywords, $mockInputStr) >= 0) {
    echo "Keyword found";
  } else {
    echo "No keyword found".strpos($config->keywords, $mockInputStr);
  }

}
