<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;

class NoopTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\NoopTransformer');
    }

    public function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    public function it_returns_original_data()
    {
        $this->transform(['foo', 'bar'])->shouldReturn(['foo', 'bar']);
    }
}
