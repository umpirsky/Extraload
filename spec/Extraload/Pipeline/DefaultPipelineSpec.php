<?php

namespace spec\Extraload\Pipeline;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Transformer\TransformerInterface;
use Extraload\Loader\LoaderInterface;

class DefaultPipelineSpec extends ObjectBehavior
{
    function let(ExtractorInterface $extractor, TransformerInterface $transformer, LoaderInterface $loader)
    {
        $this->beConstructedWith($extractor, $transformer, $loader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Pipeline\DefaultPipeline');
    }

    function it_implements_pipeline_interface()
    {
        $this->shouldImplement('Extraload\Pipeline\PipelineInterface');
    }

    function it_does_not_transform_nor_load_if_no_data_extracted(ExtractorInterface $extractor, TransformerInterface $transformer, LoaderInterface $loader)
    {
        $extractor->extract()->shouldBeCalled()->willReturn(null);
        $loader->flush()->shouldBeCalled();

        $this->process();
    }

    function it_processes_etl_sequentially(ExtractorInterface $extractor, TransformerInterface $transformer, LoaderInterface $loader)
    {
        $extractor->extract()->shouldBeCalled()->willReturn(['a1', 'b1', 'c1'], null);
        $transformer->transform(['a1', 'b1', 'c1'])->shouldBeCalled()->willReturn(['c1', 'b1', 'a1']);
        $loader->load(['c1', 'b1', 'a1'])->shouldBeCalled();
        $loader->flush()->shouldBeCalled();

        $this->process();
    }
}
