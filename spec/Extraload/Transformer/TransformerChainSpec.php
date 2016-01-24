<?php

namespace spec\Extraload\Transformer;

use PhpSpec\ObjectBehavior;
use Extraload\Transformer\TransformerInterface;

class TransformerChainSpec extends ObjectBehavior
{
    function let(TransformerInterface $reverseTransformer, TransformerInterface $capitalizeTransformer)
    {
        $this->beConstructedWith([$reverseTransformer, $capitalizeTransformer]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Transformer\TransformerChain');
    }

    function it_implements_transformer_interface()
    {
        $this->shouldImplement('Extraload\Transformer\TransformerInterface');
    }

    function it_transforms_using_all_registered_transformers(TransformerInterface $reverseTransformer, TransformerInterface $capitalizeTransformer)
    {
        $reverseTransformer->transform(['foo', 'bar'])->shouldBeCalled()->willReturn(['bar', 'foo']);
        $capitalizeTransformer->transform(['bar', 'foo'])->shouldBeCalled()->willReturn(['Bar', 'Foo']);

        $this->transform(['foo', 'bar'])->shouldReturn(['Bar', 'Foo']);
    }

    function it_throws_exception_if_trying_to_register_non_transformer()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('__construct', [['NonTransformer']]);
    }
}
