<?php

namespace Extraload\Pipeline;

use Extraload\Extractor\ExtractorInterface;
use Extraload\Transformer\TransformerInterface;
use Extraload\Loader\LoaderInterface;

class DefaultPipeline implements PipelineInterface
{
    private $extractor;
    private $transformer;
    private $loader;

    public function __construct(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader
    ) {
        $this->extractor = $extractor;
        $this->transformer = $transformer;
        $this->loader = $loader;
    }

    public function process()
    {
        while (null !== $extracted = $this->extractor->extract()) {
            $transformed = $this->transformer->transform($extracted);

            $this->loader->load($transformed);
        }

        $this->loader->flush();
    }
}
