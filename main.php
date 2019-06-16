<?php
require_once './DmWebhook.php';

$obj = new DmWebook();
$mockInputStr = "hello";

foreach($obj->getAllCOnfig() as $config) {

  if(isset($config->keywords) && strpos($config->keywords, $mockInputStr) >= 0) {
    $obj->log("Key found:".$mockInputStr);
    
  } else {
    $obj->log("Key not found");
  }

}
