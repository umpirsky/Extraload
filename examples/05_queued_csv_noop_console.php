#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Extraload\Pipeline\QueuedPipeline;
use Extraload\Extractor\CsvExtractor;
use Extraload\Extractor\QueuedExtractor;
use Extraload\Transformer\NoopTransformer;
use Extraload\Loader\QueuedLoader;
use Extraload\Loader\ConsoleLoader;
use Extraload\Loader\MessageLoader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;
use Ko\ProcessManager;
use Ko\AmqpBroker;

$broker = new AmqpBroker(Yaml::parse(file_get_contents(__DIR__.'/config.yml')));
$loader = new ConsoleLoader(
    new Table($output = new ConsoleOutput())
);

(new QueuedPipeline(
    new QueuedExtractor(
        new CsvExtractor(
            new \SplFileObject(__DIR__.'/../fixtures/books.csv')
        ),
        $broker
    ),
    new NoopTransformer(),
    new QueuedLoader(
        new MessageLoader($loader),
        $broker
    ),
    new ProcessManager()
))->process();
