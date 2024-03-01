<?php

class DB
{
   private $host;
   private $user;
   private $pass;
   private $dbName;
   private static $instance;
   private $connection;

   private function __construct()
   {
      $env = $GLOBALS['env'];
      $this->host = $env["DB_HOST"];
      $this->user = $env["DB_USERNAME"];
      $this->pass = $env["DB_PASSWORD"];
      $this->dbName = $env["DB_DATABASE"];
      $this->connect();
   }

   // singleton pattern
   static function getInstance()
   {
      if (!self::$instance) {
         self::$instance = new self();
      }
      return self::$instance;
   }

   function connect()
   {
      $this->connection =  new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->user, $this->pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }

   public function getConnection()
   {
      return $this->connection;
   }
}
