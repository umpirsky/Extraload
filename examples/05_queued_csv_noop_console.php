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
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Ko\ProcessManager;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('extracted');
$loader = new ConsoleLoader(
    new Table($output = new ConsoleOutput())
);

pcntl_signal(SIGINT, function() use ($loader) {
    $loader->flush();
    exit;
});

(new QueuedPipeline(
    new QueuedExtractor(
        new CsvExtractor(
            new \SplFileObject(__DIR__.'/../fixtures/books.csv')
        ),
        $channel,
        'extracted'
    ),
    new NoopTransformer(),
    new QueuedLoader(
        new MessageLoader($loader),
        $channel,
        'extracted'
    ),
    new ProcessManager(),
    $connection
))->process();
