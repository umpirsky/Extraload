<?php

namespace Extraload\Loader;

use PhpAmqpLib\Channel\AMQPChannel;

class QueuedLoader implements LoaderInterface
{
    private $loader;
    private $channel;
    private $queue;

    public function __construct(LoaderInterface $loader, AMQPChannel $channel, $queue)
    {
        $this->loader = $loader;
        $this->channel = $channel;
        $this->queue = $queue;
    }

    public function load($data = null)
    {
        $this->channel->basic_consume($this->queue, '', false, false, false, false, [$this->loader, 'load']);

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function flush()
    {
        $this->loader->flush();
    }
}
