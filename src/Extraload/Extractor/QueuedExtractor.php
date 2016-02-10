<?php

namespace Extraload\Extractor;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class QueuedExtractor implements ExtractorInterface
{
    private $extractor;
    private $broker;
    private $routingKey;

    public function __construct(ExtractorIteratorInterface $extractor, $broker, $routingKey)
    {
        $this->extractor = $extractor;
        $this->broker = $broker;
        $this->routingKey = $routingKey;
    }

    public function extract()
    {
        while (null !== $extracted = $this->extractor->extract()) {
            $this->broker->getProducer('extractor')->publish(json_encode($extracted));
        }
    }
}
