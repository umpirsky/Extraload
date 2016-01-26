<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Extraload\Transformer\TransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyTransformerSpec extends ObjectBehavior
{
    function let(TransformerInterface $transformer)
    {
        $this->beConstructedWith($transformer, PropertyAccess::createPropertyAccessor(), '[bar]');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\PropertyTransformer');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    function it_applies_transformer_on_given_path(TransformerInterface $transformer)
    {
        $transformer->transform('bar')->shouldBeCalled()->willReturn('Bar');

        $this->transform(['foo' => 'foo', 'bar' => 'bar'])->shouldReturn(['foo' => 'foo', 'bar' => 'Bar']);
    }
}
