<?php

namespace Extraload\Extractor\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Extractor\ExtractorInterface;

class QueryExtractor implements ExtractorInterface
{
    protected $stmt;

    protected $position;

    protected $data;

    public function __construct(Connection $conn, string $sql, array $values = [])
    {
        $this->stmt = $conn->prepare($sql);

        foreach ($values as $value) {
            $this->stmt->bindValue(
                $value['parameter'],
                $value['value'],
                $value['data_type'] ?? null
            );
        }

        $this->stmt->execute();

        $this->position = 0;

        $this->data = [];
    }

    public function extract()
    {
        if (false !== $this->data[$this->position] = $this->stmt->fetch()) {
            $data = $this->current();
            $this->next();
            return $data;
        }
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
