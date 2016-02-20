<?php

namespace Extraload\Transformer;

use Ko\AmqpBroker;

class TransformerConsumer implements TransformerInterface
{
    private $transformer;
    private $broker;
    private $producer;

    public function __construct(TransformerInterface $transformer, AmqpBroker $broker, $producer)
    {
        $this->transformer = $transformer;
        $this->broker = $broker;
        $this->producer = $producer;
    }

    public function transform($data = null)
    {
        $this->broker->getProducer($this->producer)->publish(serialize(
            $this->transformer->transform($data)
        ));
    }
}
