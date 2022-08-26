<?php

declare(strict_types=1);

namespace Aqua\DatabaseConnection\Exception;

use PDOException;

class DatabaseConnectionException extends \PDOException
{

    protected $massage;

    protected $code;

    public function __construct($message = null, $code = null)
    {
        $this->massage = $message;
        $this->code = $code;
    }
}