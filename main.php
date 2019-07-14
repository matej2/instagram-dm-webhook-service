<?php
require_once __DIR__."/DmWebhook.php";

$obj = new DmWebook();


$messages = $obj->getLastDM();


foreach($obj->getWebhooks() as $whIndex => $wh) {

  $obj->logger->log("Config index: ".$whIndex);
  $obj->logger->log("Keywords: ".$wh->keywords);

  if(sizeof($messages) === 0) {
    $obj->logger->log("No messages found");
  }

  foreach($messages as $message) {
    //$obj->log("Message sent".($obj->processMessage($wh,$message)) ? "true" : "false");
    $obj->processMessage($wh,$message);
  }
}
