<?php

namespace Extraload\Loader;

use PhpAmqpLib\Channel\AMQPChannel;
use Ko\AmqpBroker;

class QueuedLoader implements LoaderInterface
{
    private $loader;
    private $broker;

    public function __construct(LoaderInterface $loader, AmqpBroker $broker)
    {
        $this->loader = $loader;
        $this->broker = $broker;
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
