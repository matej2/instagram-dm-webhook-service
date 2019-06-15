<?php
require_once './DmWebhookService.php';

$obj = new DmWebook();
var_dump($obj->send($obj->getSingleConfig(1)));
