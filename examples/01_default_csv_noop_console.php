#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Extraload\Pipeline\DefaultPipeline;
use Extraload\Extractor\CsvExtractor;
use Extraload\Transformer\NoopTransformer;
use Extraload\Loader\ConsoleLoader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

(new DefaultPipeline(
    new CsvExtractor(
        new \SplFileObject(__DIR__.'/../fixtures/books.csv')
    ),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
