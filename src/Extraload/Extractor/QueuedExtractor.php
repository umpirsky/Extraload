<?php

namespace Extraload\Extractor;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class QueuedExtractor implements ExtractorInterface
{
    private $extractor;
    private $channel;
    private $routingKey;

    public function __construct(ExtractorIteratorInterface $extractor, AMQPChannel $channel, $routingKey)
    {
        $this->extractor = $extractor;
        $this->channel = $channel;
        $this->routingKey = $routingKey;
    }

    public function extract()
    {
        while (null !== $extracted = $this->extractor->extract()) {
            $this->channel->basic_publish(new AMQPMessage(serialize($extracted)), null, $this->routingKey);
        }

        $this->channel->close();
    }
}
