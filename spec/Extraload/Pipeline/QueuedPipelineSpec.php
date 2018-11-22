<?php

namespace spec\Extraload\Pipeline;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Extraload\Extractor\QueuedExtractor;
use Extraload\Transformer\QueuedTransformer;;
use Extraload\Loader\QueuedLoader;
use Ko\ProcessManager;

class QueuedPipelineSpec extends ObjectBehavior
{
    function let(
        QueuedExtractor $extractor,
        QueuedTransformer $transformer,
        QueuedLoader $loader,
        ProcessManager $processManager
    )
    {
        $this->beConstructedWith($extractor, $transformer, $loader, $processManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Pipeline\QueuedPipeline');
    }

    function it_implements_pipeline_interface()
    {
        $this->shouldImplement('Extraload\Pipeline\PipelineInterface');
    }
}
