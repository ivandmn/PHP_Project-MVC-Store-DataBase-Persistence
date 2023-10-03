<?php

namespace store\model\persist;
/**
 * PDO database connection.
 *
 * @author ivandmn
 */
class StorePdoDb
{

    private $dsn;
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $opt;

    public function __construct()
    {
        //connection data.
        $this->host = 'localhost';
        $this->db = 'storedb';
        $this->user = 'provenusr';
        $this->pass = 'provenpass';
        $this->charset = 'utf8';
        $this->dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", $this->host, $this->db, $this->charset);
        //$this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $this->opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    public function getConnection()
    {
        //PDO object creation.
        $connection = new \PDO($this->dsn, $this->user, $this->pass, $this->opt);
        return $connection;
    }

}

?>