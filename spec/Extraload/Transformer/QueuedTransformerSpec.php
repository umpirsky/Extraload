<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ko\AmqpBroker;
use Ko\RabbitMq\Consumer;
use Extraload\Transformer\TransformerConsumer;

class QueuedTransformerSpec extends ObjectBehavior
{
    function let(TransformerConsumer $transformer, AmqpBroker $broker)
    {
        $this->beConstructedWith($transformer, $broker, 'extracted');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\QueuedTransformer');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    function it_consumes_messages_extracted_messages(TransformerConsumer $transformer, AmqpBroker $broker, Consumer $consumer)
    {
        $broker->getConsumer('extracted')->shouldBeCalled()->willReturn($consumer);
        $consumer->consume([$transformer, 'transform'], AMQP_AUTOACK);

        $this->transform();
    }
}
