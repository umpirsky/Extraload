<?php

namespace Extraload\Extractor;

use Ko\AmqpBroker;

class QueuedExtractor implements ExtractorInterface
{
    private $extractor;
    private $broker;
    private $proucer;

    public function __construct(ExtractorIteratorInterface $extractor, AmqpBroker $broker, $proucer)
    {
        $this->extractor = $extractor;
        $this->broker = $broker;
        $this->proucer = $proucer;
    }

    public function extract()
    {
        while (null !== $extracted = $this->extractor->extract()) {
            $this->broker->getProducer($this->proucer)->publish(serialize($extracted));
        }
    }
}
