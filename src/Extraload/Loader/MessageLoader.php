<?php

namespace Extraload\Loader;

use Extraload\Loader\LoaderInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MessageLoader implements LoaderInterface
{
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($data = null)
    {
        if (!$data instanceof AMQPMessage) {
            throw new \InvalidArgumentException('MessageLoader can only load AMQPMessage.');
        }

        $this->loader->load(unserialize($data->body));
    }

    public function flush()
    {
        $this->loader->flush();
    }
}
