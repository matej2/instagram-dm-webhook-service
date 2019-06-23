<?php

require_once './config.php';


$db_conn = new databaseConnection();

$sql = "SELECT * FROM ".$db_conn->db_name." WHERE message_sent = 0";

$stmt = $db_conn->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    //Send messages in here
}