<?php
    public function diff(DateTimeInterface $targetObject, $absolute = false)
    {
        // TODO: Implement diff() method.
    }

    public function format($format)
    {
        // TODO: Implement format() method.
    }

    public function getOffset()
    {
        // TODO: Implement getOffset() method.
    }

    public function getTimestamp()
    {
        // TODO: Implement getTimestamp() method.
    }

    public function getTimezone()
    {
        // TODO: Implement getTimezone() method.
    }

    public function __wakeup()
    {
        // TODO: Implement __wakeup() method.
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