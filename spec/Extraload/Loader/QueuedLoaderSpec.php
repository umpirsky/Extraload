<?php

namespace spec\Extraload\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Loader\LoaderInterface;
use Ko\AmqpBroker;
use Ko\RabbitMq\Consumer;

class QueuedLoaderSpec extends ObjectBehavior
{
    function let(LoaderInterface $loader, AmqpBroker $broker)
    {
        $this->beConstructedWith($loader, $broker, 'transformed');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\QueuedLoader');
    }

    function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    function it_consumes_messages_from_given_channel(LoaderInterface $loader, AmqpBroker $broker, Consumer $consumer)
    {
        $broker->getConsumer('transformed')->shouldBeCalled()->willReturn($consumer);
        $consumer->consume([$loader, 'load'], AMQP_AUTOACK);

        $this->load();
    }
}
