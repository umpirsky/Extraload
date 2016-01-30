<?php

namespace spec\Extraload\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Loader\LoaderInterface;
use PhpAmqpLib\Channel\AMQPChannel;

class QueuedLoaderSpec extends ObjectBehavior
{
    function let(LoaderInterface $loader, AMQPChannel $channel)
    {
        $this->beConstructedWith($loader, $channel, 'transformed');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\QueuedLoader');
    }

    function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    function it_publihes_messages_from_given_channel(LoaderInterface $loader, AMQPChannel $channel)
    {
        $channel->basic_consume('transformed', '', false, false, false, false, [$loader, 'load']);
    }
}
