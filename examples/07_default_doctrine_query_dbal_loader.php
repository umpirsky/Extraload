#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\Connection;
use Extraload\Extractor\Doctrine\QueryExtractor;
use Extraload\Loader\Doctrine\DbalLoader;
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

(new DefaultPipeline(
    new QueryExtractor($conn, 'SELECT * FROM books'),
    new NoopTransformer(),
    new DbalLoader($conn, 'my_books')
))->process();
