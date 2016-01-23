<?php

namespace Extraload\Loader\Doctrine;

use Extraload\Loader\LoaderInterface;
use Extraload\Loader\AutoFlushLoader;
use Doctrine\DBAL\Connection;

class DbalLoader extends AutoFlushLoader implements LoaderInterface
{
    private $connection;
    private $tableName;

    public function __construct(Connection $connection, $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function load($data)
    {
        return $this->connection->insert($this->tableName, $data);
    }
}
