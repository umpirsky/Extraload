<?php

namespace Extraload\Loader;

use Symfony\Component\Console\Helper\Table;

class ConsoleLoader implements LoaderInterface
{
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function load($data)
    {
        $this->table->addRow($data);
    }

    public function flush()
    {
        $this->table->render();
    }
}
