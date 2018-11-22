<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Transformer\TransformerInterface;
use Ko\AmqpBroker;
use Ko\RabbitMq\Producer;

class TransformerConsumerSpec extends ObjectBehavior
{
    function let(TransformerInterface $transformer, AmqpBroker $broker)
    {
        $this->beConstructedWith($transformer, $broker, 'transformed');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\TransformerConsumer');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    function it_publihes_transformed_messages(TransformerInterface $transformer, AmqpBroker $broker, Producer $producer)
    {
        $transformer->transform(null)->willReturn(['foo' => 'bar']);
        $broker->getProducer('transformed')->shouldBeCalled()->willReturn($producer);
        $producer->publish(serialize(['foo' => 'bar']))->shouldBeCalled();

        $this->transform();
    }
}
