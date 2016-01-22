<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackTransformerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('array_reverse');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\CallbackTransformer');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldHaveType('Extraload\Transformer\TransformerInterface');
    }

    function it_transforms_data_using_callback()
    {
        $this->transform(['foo', 'bar'])->shouldReturn(['bar', 'foo']);
    }
}
