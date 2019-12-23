#!/usr/bin/env php
<?php

require __DIR__.'/../../vendor/autoload.php';
require './mysql-bootstrap.php';

use Extraload\Extractor\Doctrine\QueryExtractor;
use Extraload\Loader\Doctrine\DbalLoader;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Transformer\MapFieldsTransformer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

(new DefaultPipeline(
    new QueryExtractor($conn, 'SELECT * FROM books'),
    new MapFieldsTransformer([
        'author' => 'writer',
        'title' => 'book'
    ]),
    new DbalLoader($conn, 'your_books')
))->process();
