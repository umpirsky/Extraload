<?php

namespace spec\Extraload\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ko\AmqpBroker;
use Ko\RabbitMq\Producer;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Extractor\ExtractorIteratorInterface;

class QueuedExtractorSpec extends ObjectBehavior
{
    function let(ExtractorIteratorInterface $extractor, AmqpBroker $broker)
    {
        $this->beConstructedWith($extractor, $broker, 'extracted');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Extractor\QueuedExtractor');
    }

    function it_implements_extractor_interface()
    {
        $this->shouldHaveType('Extraload\Extractor\ExtractorInterface');
    }

    function it_publihes_messages_to_given_channel(ExtractorIteratorInterface $extractor, AmqpBroker $broker, Producer $producer)
    {
        $extractor->extract()->shouldBeCalled()->willReturn(['foo' => 'bar'], null);
        $broker->getProducer('extracted')->shouldBeCalled()->willReturn($producer);
        $producer->publish(serialize(['foo' => 'bar']));

        $this->extract();
    }
}
