<?php

namespace Extraload\Loader\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Loader\AutoFlushLoader;
use Extraload\Loader\LoaderInterface;

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
