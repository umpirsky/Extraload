#!/usr/bin/env php
<?php

require __DIR__.'/../../vendor/autoload.php';
require './mysql-bootstrap.php';

use Extraload\Extractor\Doctrine\QueryExtractor;
use Extraload\Loader\ConsoleLoader;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Transformer\NoopTransformer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

$sql = "SELECT * FROM books WHERE author = :author";
$values = [
    [
        'parameter' => ':author',
        'value' => 'Dante Alighieri',
        'data_type' => PDO::PARAM_STR // optional
    ]
];

(new DefaultPipeline(
    new QueryExtractor($conn, $sql, $values),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
