<?php

namespace Extraload\Extractor\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Extractor\Doctrine\AbstractExtractor;

class PreparedQueryExtractor extends AbstractExtractor
{
    public function __construct(Connection $conn, string $sql, array $values)
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
    }
}
