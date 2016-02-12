#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Ko\ProcessManager;
use Ko\Process;

$config = Yaml::parse(file_get_contents(__DIR__.'/config.yml'));
$broker = new Ko\AmqpBroker($config);
$processManager = new ProcessManager();

$processManager->fork(function(Process $process) use ($broker) {
    $consumer = $broker->getConsumer('extractor');
    $message = 'Hello world ' . time();
    echo 'Receiving message `' . $message . '`' . PHP_EOL ;

    $consumer->consume(function (AMQPEnvelope $envelope) {
        echo 'Receive `' . $envelope->getBody() . PHP_EOL;
        return true;
    }, AMQP_AUTOACK);
});
$processManager->fork(function(Process $process) use ($broker) {
    $producer = $broker->getProducer('extractor');
    $message = 'Hello world ' . time();
    echo 'Sending message `' . $message . '`' . PHP_EOL ;
    $producer->publish($message);
});

$processManager->wait();
