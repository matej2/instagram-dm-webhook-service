<?php

define('ROOT',__DIR__);

require_once __DIR__."/src/DmWebhook.php";

$obj = new DmWebook();


$messages = $obj->getLastDM();

do {
  $obj->logger->log("Current index___:".$obj->currConfInd);
  if(sizeof($messages) === 0) {
    $obj->logger->log("No messages found");
  }

  foreach($messages as $message) {
    //$obj->log("Message sent".($obj->processMessage($wh,$message)) ? "true" : "false");
    $obj->processMessage($message);
  }
  $obj->logger->log("End foreach in while");
} while($obj->hasNextConf() == true);