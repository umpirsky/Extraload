<?php

namespace Extraload\Loader;

use Extraload\Loader\LoaderInterface;

class MessageLoader extends AutoFlushLoader implements LoaderInterface
{
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($data = null)
    {
        if (!$data instanceof \AMQPEnvelope) {
            throw new \InvalidArgumentException('MessageLoader can only load AMQPEnvelope.');
        }

        $this->loader->load(unserialize($data->getBody()));

        $this->loader->flush();
    }
}
