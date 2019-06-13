<?php
require_once './DmWebhookService.php';

$obj = new InstagramChatBot();
var_dump($obj->send($obj->getSingleConfig(1)));
