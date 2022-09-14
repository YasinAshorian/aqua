<?php
namespace YAshourian\Aqua\DatabaseConnection\Exception;

use PDOException;

class DatabaseConnectionException extends \PDOException
{

  /**
   * Main constructor class witch overrides the parent constructor and set massage and the code
   * properties witch is optional
   * @param  string $message
   * @param  int $code
   * @return void
   */
    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}