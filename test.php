#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Ko\ProcessManager;
use Ko\Process;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('q1');
$processManager = new ProcessManager();

$processManager->fork(function (Process $process) use ($channel) {
    echo 'Publishing from '.$process->getPid().PHP_EOL;

    $channel->basic_publish(new AMQPMessage('Hello from '.$process->getPid()), null, 'q1');
    $channel->close();
});

$processManager->fork(function (Process $process) use ($channel) {
    echo 'Consuming from '.$process->getPid().PHP_EOL;

    $channel->basic_consume('q1', '', false, false, false, false, function ($message) {
        echo 'Consuming message: '.$message->body.PHP_EOL;
    });

    while (count($channel->callbacks)) {
        $channel->wait();
    }
});

$processManager->wait();
