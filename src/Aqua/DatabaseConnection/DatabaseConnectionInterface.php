<?php

namespace YAshourian\Aqua\DatabaseConnection;

use PDO as PDOAlias;

interface DatabaseConnectionInterface
{

   /**
    * create a new database connection
    * @return PDOAlias
    */
    public function open() : PDOAlias;


   /**
    * close database connection
    * @return void
    */
    public function close() : void;

}