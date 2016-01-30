<?php

namespace spec\Extraload\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpAmqpLib\Channel\AMQPChannel;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Extractor\ExtractorIteratorInterface;

class QueuedExtractorSpec extends ObjectBehavior
{
    function let(ExtractorIteratorInterface $extractor, AMQPChannel $channel)
    {
        $this->beConstructedWith($extractor, $channel, 'extracted');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Extractor\QueuedExtractor');
    }

    function it_implements_extractor_interface()
    {
        $this->shouldHaveType('Extraload\Extractor\ExtractorInterface');
    }

    function it_publihes_messages_to_given_channel(ExtractorIteratorInterface $extractor, AMQPChannel $channel)
    {
        $extractor->extract()->shouldBeCalled()->willReturn(['foo' => 'bar'], null);
        $channel->basic_publish(Argument::any(), null, 'extracted')->shouldBeCalled();
        $channel->close()->shouldBeCalled();

        $this->extract();
    }
}
