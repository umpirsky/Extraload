<?php

namespace Extraload\Loader;

use PhpAmqpLib\Channel\AMQPChannel;
use Ko\AmqpBroker;

class QueuedLoader extends AutoFlushLoader implements LoaderInterface
{
    private $loader;
    private $broker;
    private $consumer;

    public function __construct(LoaderInterface $loader, AmqpBroker $broker, $consumer)
    {
        $this->loader = $loader;
        $this->broker = $broker;
        $this->consumer = $consumer;
    }

    public function load($data = null)
    {
        $this->broker->getConsumer($this->consumer)->consume([$this->loader, 'load'], AMQP_AUTOACK);
    }
}
