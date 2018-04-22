<?php

namespace Extraload\Extractor\Doctrine;

use Doctrine\DBAL\Connection;
use Extraload\Extractor\Doctrine\AbstractExtractor;

class QueryExtractor extends AbstractExtractor
{
    public function __construct(Connection $conn, string $sql)
    {
        $this->stmt = $conn->query($sql);
        $this->position = 0;
        $this->data = [];
    }
}
