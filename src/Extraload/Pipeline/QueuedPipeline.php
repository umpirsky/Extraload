<?php

namespace Extraload\Pipeline;

use Extraload\Extractor\QueuedExtractor;
use Extraload\Transformer\QueuedTransformer;
use Extraload\Loader\QueuedLoader;
use Ko\ProcessManager;
use Ko\Process;

class QueuedPipeline implements PipelineInterface
{
    private $extractor;
    private $transformer;
    private $loader;
    private $processManager;

    public function __construct(
        QueuedExtractor $extractor,
        QueuedTransformer $transformer,
        QueuedLoader $loader,
        ProcessManager $processManager
    )
    {
        $this->extractor = $extractor;
        $this->transformer = $transformer;
        $this->loader = $loader;
        $this->processManager = $processManager;
    }

    public function process()
    {
        $this->processManager->fork(function(Process $process) {
            $this->extractor->extract();
        });

        $this->processManager->fork(function(Process $process) {
            $this->transformer->transform();
        });

        $this->processManager->fork(function(Process $process) {
            $this->loader->load();
        });

        $this->processManager->wait();
    }
}
