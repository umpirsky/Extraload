#!/usr/bin/env php
<?php

require __DIR__.'/../../vendor/autoload.php';

use Doctrine\DBAL\Connection;

$config = new \Doctrine\DBAL\Configuration();
$connectionParams = array(
    'dbname' => 'extraload_fixtures',
    'user' => 'extraload_fixtures',
    'password' => 'password',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
