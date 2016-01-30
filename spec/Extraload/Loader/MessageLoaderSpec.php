<?php

namespace spec\Extraload\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Loader\LoaderInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MessageLoaderSpec extends ObjectBehavior
{
    function let(LoaderInterface $loader)
    {
        $this->beConstructedWith($loader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\MessageLoader');
    }

    function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    function it_throws_exception_if_not_loading_message()
    {
        $this->shouldThrow('InvalidArgumentException')->duringLoad(['foo' => 'bar']);
    }

    function it_loads_data_from_message_using_given_loader(LoaderInterface $loader)
    {
        $message = new AMQPMessage(serialize(['foo' => 'bar']));
        $loader->load(['foo' => 'bar'])->shouldBeCalled();

        $this->load($message);
    }
}
