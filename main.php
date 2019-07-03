<?php
require_once "./DmWebhook.php";

$obj = new DmWebook();


$messages = $obj->getLastDM();


foreach($obj->getWebhooks() as $whIndex => $wh) {

  $obj->logger->log("Config index: ".$whIndex);
  $obj->logger->log("Keywords: ".$wh->keywords);

  foreach($messages as $message) {
    //$obj->log("Message sent".($obj->processMessage($wh,$message)) ? "true" : "false");
    $obj->processMessage($wh,$message);
  }
}
