<?php
namespace YAshourian\Aqua\DatabaseConnection;

use Aqua\DatabaseConnection\Exception\DatabaseConnectionException;
use PDO ;

class DatabaseConnection implements DatabaseConnectionInterface
{
  /**
   * @var PDO;
   */
  protected PDO $dbh;

  /**
   * @var array
   */
  protected array $credentials;

  /**
   * Main constructor class
   * @return void
   */
    public function __construct(array $credentials)
    {

        $this->credentials = $credentials;
    }

   /**
    * @inheritDoc
    */
    public function open(): PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT=> true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params,
            );
        }catch (\Exception $exception){
            throw new DatabaseConnectionException($exception->getMessage(), (int)$exception->getCode());
        }

        return $this->dbh;
    }


   /**
    * @inheritDoc
    */
    public function close(): void
    {
        $this->dbh = null;
    }
}