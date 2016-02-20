<?php

namespace Extraload\Transformer;

use Ko\AmqpBroker;

class QueuedTransformer implements TransformerInterface
{
    private $transformer;
    private $broker;
    private $consumer;

    public function __construct(TransformerConsumer $transformer, AmqpBroker $broker, $consumer)
    {
        $this->transformer = $transformer;
        $this->broker = $broker;
        $this->consumer = $consumer;
    }

    public function transform($data = null)
    {
        $this->broker->getConsumer($this->consumer)->consume([$this->transformer, 'transform'], AMQP_AUTOACK);
    }
}
