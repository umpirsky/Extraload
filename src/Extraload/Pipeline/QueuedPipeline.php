<?php

namespace Extraload\Pipeline;

use Extraload\Extractor\QueuedExtractor;
use Extraload\Transformer\TransformerInterface;;
use Extraload\Loader\QueuedLoader;
use PhpAmqpLib\Connection\AbstractConnection;
use Ko\ProcessManager;
use Ko\Process;

class QueuedPipeline implements PipelineInterface
{
    private $extractor;
    private $transformer;
    private $loader;
    private $processManager;
    private $connection;

    public function __construct(
        QueuedExtractor $extractor,
        TransformerInterface $transformer,
        QueuedLoader $loader,
        ProcessManager $processManager,
        AbstractConnection $connection
    )
    {
        $this->extractor = $extractor;
        $this->transformer = $transformer;
        $this->loader = $loader;
        $this->processManager = $processManager;
        $this->connection = $connection;
    }

    public function process()
    {
        $this->processManager->fork(function(Process $process) {
            echo 'Extracting... ' . PHP_EOL;
            $this->extractor->extract();
        });

        $this->processManager->fork(function(Process $process) {
            echo 'Loading... ' . PHP_EOL;
            $this->loader->load();
        });

        $this->processManager->wait();
    }
}
