<?php
namespace YAshourian\Aqua\LiquidOrm\DataMapper;

use YAshourian\Aqua\DatabaseConnection\DatabaseConnectionInterface;
use DateTimeInterface;

class DataMapper implements DataMapperInterface
{
    /** @var DatabaseConnectionInterface $dbh */
    private DatabaseConnectionInterface $dbh;

    /** @var \PDOStatement $statement */
    private \PDOStatement $statement;

    /**
     * Main Constructor class
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     *
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws DadaMapperException
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DadaMapperException($errorMessage);
        }
    }

    /**
     * Check the incoming argument $value is an array else throw an exception
     *
     * @param array $value
     * @return void
     * @throws DadaMapperException
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DadaMapperException('Your argument needs to be an array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): DataMapperInterface
    {
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param [type] $value
     * @return void
     */
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

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        if (is_array($fields)) {

            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     *
     * @param array $fields
     * @return \PDOStatement
     * @throws DadaMapperException
     */
    protected function bindValues(array $fields): \PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement. Similar to
     * above but optimised for search queries
     *
     * @param array $fields
     * @return mixed
     * @throws DadaMapperException
     */
    protected function bindSearchValues(array $fields): \PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function execute()
    {
        if ($this->statement)
            return $this->statement->execute();
    }

    /**
     * @inheritDoc
     *
     * @return object
     */
    public function result(): object
    {
//        if ($this->statement)
//            return $this->statement->fetch(\PDO::\FETCH_OBJ);
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function results(): array
    {
//        if ($this->statement)
//            return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     *
     * @return integer
     */
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