<?php

namespace Extraload\Loader;

use PhpAmqpLib\Channel\AMQPChannel;

class QueuedLoader implements LoaderInterface
{
    private $loader;
    private $broker;
    private $queue;

    public function __construct(LoaderInterface $loader, $broker, $queue)
    {
        $this->loader = $loader;
        $this->broker = $broker;
        $this->queue = $queue;
    }

    public function load($data = null)
    {
        $this->broker->getConsumer('extractor')->consume([$this->loader, 'load'], AMQP_AUTOACK);
    }

    public function flush()
    {
        $this->loader->flush();
    }
}
