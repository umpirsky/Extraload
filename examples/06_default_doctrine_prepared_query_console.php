#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\Connection;
use Extraload\Extractor\Doctrine\PreparedQueryExtractor;
use Extraload\Loader\ConsoleLoader;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Transformer\NoopTransformer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

$config = new \Doctrine\DBAL\Configuration();
$connectionParams = array(
    'dbname' => 'extraload_fixtures',
    'user' => 'extraload_fixtures',
    'password' => 'password',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

$sql = "SELECT * FROM books WHERE author = :author";
$values = [
    [
        'parameter' => ':author',
        'value' => 'Dante Alighieri',
        'data_type' => PDO::PARAM_STR // optional
    ]
];

(new DefaultPipeline(
    new PreparedQueryExtractor($conn, $sql, $values),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
