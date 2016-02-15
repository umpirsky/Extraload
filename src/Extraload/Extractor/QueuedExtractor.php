<?php

namespace Extraload\Extractor;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Ko\AmqpBroker;

class QueuedExtractor implements ExtractorInterface
{
    private $extractor;
    private $broker;

    public function __construct(ExtractorIteratorInterface $extractor, AmqpBroker $broker)
    {
        $this->extractor = $extractor;
        $this->broker = $broker;
    }

    public function extract()
    {
        while (null !== $extracted = $this->extractor->extract()) {
            $this->broker->getProducer('extractor')->publish(json_encode($extracted));
        }
    }
}
