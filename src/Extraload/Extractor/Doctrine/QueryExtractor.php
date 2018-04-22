<?php

namespace Extraload\Extractor\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Extractor\ExtractorInterface;

class QueryExtractor implements ExtractorInterface
{
    private $stmt;

    private $position;

    private $data;

    public function __construct(Connection $conn, string $sql)
    {
        $this->stmt = $conn->query($sql);
        $this->position = 0;
        $this->data = [];
    }

    public function extract()
    {
        if (count($this->data) >= $this->stmt->rowCount()) {
            return;
        }

        $this->data[$this->position] = $this->stmt->fetch();

        $data = $this->current();

        $this->next();

        return $data;
    }

    public function current()
    {
        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position += 1;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }
}
