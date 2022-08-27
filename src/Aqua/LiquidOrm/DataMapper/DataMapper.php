<?php

declare(strict_types=1);

namespace Aqua\LiquidOrm\DataMapper;

use Aqua\DatabaseConnection\DatabaseConnectionInterface;
use DateInterval;
use DateTimeInterface;
use DateTimeZone;

class DataMapper implements DataMapperInterface
{
    /**
     * @var DatabaseConnectionInterface $dbh ;
     */
    private DatabaseConnectionInterface $dbh;

    /**
     * @var \PDOStatement $stmt ;
     */
    private \PDOStatement $stmt;

    /**
     * Main Constructor class
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DadaMapperException($errorMessage);
        }
    }

    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DadaMapperException('Your argument needs to be an array');
        }
    }

    public function prepare(string $sqlQuery): DataMapperInterface
    {
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = \PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = \PDO::PARAM_NULL;
                    break;
                default :
                    $dataType = \PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DadaMapperException $exception) {
            throw $exception;
        }
    }


    public function bindParameters(array $fields, bool $isSearch = false)
    {
        if (is_array($fields)) {

            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    protected function bindValues(array $fields)
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    protected function bindSearchValues(array $fields)
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }

    public function execute(): void
    {
        if ($this->statement)
            return $this->statement->execute();
    }

    public function result(): object
    {
        if ($this->statement)
            return $this->statement->fetch(\PDO::\FETCH_OBJ);
    }

    public function results(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll();
    }


    public function getLastId(): int
    {
        try {
            if ($this->dbh->open()) {
                $lastID = $this->dbh->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (\Throwable $throwable) {
            throw  $throwable;
        }
    }
}