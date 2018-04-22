#!/usr/bin/env php
<?php

require __DIR__.'/../../vendor/autoload.php';
require './mysql-bootstrap.php';

use Extraload\Extractor\Doctrine\QueryExtractor;
use Extraload\Loader\Doctrine\DbalLoader;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Transformer\NoopTransformer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

(new DefaultPipeline(
    new QueryExtractor($conn, 'SELECT * FROM books'),
    new NoopTransformer(),
    new DbalLoader($conn, 'my_books')
))->process();
