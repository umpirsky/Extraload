<?php

namespace Extraload\Extractor\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Extractor\ExtractorInterface;

class PreparedQueryExtractor implements ExtractorInterface
{
    private $position = 0;

    private $data;

    public function __construct(Connection $conn, string $sql, array $values)
    {
        $this->position = 0;

        $stmt = $conn->prepare($sql);

        foreach ($values as $value) {
            $stmt->bindValue(
                $value['parameter'],
                $value['value'],
                $value['data_type'] ?? null
            );
        }

        $stmt->execute();

        $this->data = $stmt->fetchAll();
    }

    public function extract()
    {
        if ($this->position >= count($this->data)) {
            return;
        }

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
