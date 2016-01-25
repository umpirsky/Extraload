<?php

namespace Extraload\Pipeline;

use Extraload\Events;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Loader\LoaderInterface;
use Extraload\Transformer\TransformerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DefaultPipeline implements PipelineInterface
{
    private $extractor;
    private $transformer;
    private $loader;
    private $eventDispatcher;

    public function __construct(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->extractor = $extractor;
        $this->transformer = $transformer;
        $this->loader = $loader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function process()
    {
        $this->dispatch(Events::PRE_PROCESS);

        while (null !== $extracted = $this->extractor->extract()) {
            $this->dispatch(Events::EXTRACT, $extracted);

            $transformed = $this->transformer->transform($extracted);
            $this->dispatch(Events::TRANSFORM, $transformed);

            $this->loader->load($transformed);
            $this->dispatch(Events::LOAD);
        }

        $this->loader->flush();

        $this->dispatch(Events::POST_PROCESS);
    }

    private function dispatch($name, $subject = null)
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($name, new GenericEvent($subject));
    }
}
