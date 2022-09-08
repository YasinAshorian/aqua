<?php
    public function diff(DateTimeInterface $targetObject, $absolute = false)
    {
    }

    public function format($format)
    {
    }

    public function getOffset()
    {
    }

    public function getTimestamp()
    {
    }

    public function getTimezone()
    {
    }

    public function __wakeup()
    {
    }

declare(strict_types=1);

namespace Aqua\DatabaseConnection;

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
