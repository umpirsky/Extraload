<?php

namespace spec\Extraload\Pipeline;

use Extraload\Events;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Loader\LoaderInterface;
use Extraload\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DefaultPipelineSpec extends ObjectBehavior
{
    public function let(ExtractorInterface $extractor, TransformerInterface $transformer, LoaderInterface $loader)
    {
        $this->beConstructedWith($extractor, $transformer, $loader);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Pipeline\DefaultPipeline');
    }

    public function it_implements_pipeline_interface()
    {
        $this->shouldImplement('Extraload\Pipeline\PipelineInterface');
    }

    public function it_does_not_transform_nor_load_if_no_data_extracted(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader
    ) {
        $extractor->extract()->shouldBeCalled()->willReturn(null);
        $transformer->transform(Argument::any())->shouldNotBeCalled();
        $loader->flush()->shouldBeCalled();

        $this->process();
    }

    public function it_processes_etl_sequentially(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader
    ) {
        $extractor->extract()->shouldBeCalled()->willReturn(['a1', 'b1', 'c1'], null);
        $transformer->transform(['a1', 'b1', 'c1'])->shouldBeCalled()->willReturn(['c1', 'b1', 'a1']);
        $loader->load(['c1', 'b1', 'a1'])->shouldBeCalled();
        $loader->flush()->shouldBeCalled();

        $this->process();
    }

    public function it_dispatches_events_during_etl_processesing(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->beConstructedWith($extractor, $transformer, $loader, $eventDispatcher);

        $extractor->extract()->shouldBeCalled()->willReturn(['a1', 'b1', 'c1'], null);
        $transformer->transform(['a1', 'b1', 'c1'])->shouldBeCalled()->willReturn(['c1', 'b1', 'a1']);
        $loader->load(['c1', 'b1', 'a1'])->shouldBeCalled();
        $loader->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(Events::PRE_PROCESS, Argument::any())->shouldBeCalled();
        $eventDispatcher->dispatch(Events::POST_PROCESS, Argument::any())->shouldBeCalled();
        $eventDispatcher->dispatch(Events::EXTRACT, Argument::any())->shouldBeCalled();
        $eventDispatcher->dispatch(Events::TRANSFORM, Argument::any())->shouldBeCalled();
        $eventDispatcher->dispatch(Events::LOAD, Argument::any())->shouldBeCalled();

        $this->process();
    }
}
