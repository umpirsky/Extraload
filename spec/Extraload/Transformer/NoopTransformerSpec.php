<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoopTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\NoopTransformer');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    function it_returns_original_data()
    {
        $this->transform(['foo', 'bar'])->shouldReturn(['foo', 'bar']);
    }
}
