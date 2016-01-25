<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;

class CallbackTransformerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('array_reverse');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\CallbackTransformer');
    }

    public function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    public function it_transforms_data_using_callback()
    {
        $this->transform(['foo', 'bar'])->shouldReturn(['bar', 'foo']);
    }
}
