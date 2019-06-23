<?php
require_once "./DmWebhook.php";

$obj = new DmWebook();

if($obj->getSettings()->debug == true) {
  // Input mocks - used for testing (random values)
  $messages = array(
    array(
    "message" => "Hello, this is John",
    "userId" => "894237987236589273",
    "timestamp" => "8573495804379850"
    ),
    array(
      "message" => "Hello, this is Joanne",
      "userId" => "894237987236589273",
      "timestamp" => "8573495804379850"
    ),
    array(
      "message" => "Where can i order?",
      "userId" => "894237987236589273",
      "timestamp" => "8573495804379850"
    )
  );
} else {
  $messages = $obj->getLastDM();
}


foreach($obj->getWebhooks() as $whIndex => $wh) {

  $obj->log("Config index: ".$whIndex);
  $obj->log("Keywords: ".$wh->keywords);

  foreach($messages as $message) {
    //$obj->log("Message sent".($obj->processMessage($wh,$message)) ? "true" : "false");
    $obj->processMessage($wh,$message);
  }
}
