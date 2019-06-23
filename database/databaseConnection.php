<?php

class databaseConnection
{
    private $username;
    private $password;
    private $host;
    private $db_name;
    private $db_type;

    public function __construct()
    {
        $this->username = 'root';
        $this->password = '';
        $this->host = 'localhost';
        $this->db_name = 'instagram_chat';
        $this->db_type = 'mysql';
    }

    public function connection()
    {
        try {
            $db_conn = new PDO($this->db_type.":host=".$this->host.";dbname=".$this->db_name."'",$this->username, $this->password);

            return $db_conn;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}