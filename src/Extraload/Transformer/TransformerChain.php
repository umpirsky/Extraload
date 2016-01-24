<?php

namespace Extraload\Transformer;

class TransformerChain implements TransformerInterface
{
    private $transformers;

    public function __construct(array $transformers)
    {
        foreach ($transformers as $transformer) {
            if ($transformer instanceof TransformerInterface) {
                continue;
            }

            throw new \InvalidArgumentException(
                'All transformers in the chain should implement TransformerInterface.'
            );
        }

        $this->transformers = $transformers;
    }

    public function transform($data)
    {
        foreach ($this->transformers as $transformer) {
            $data = $transformer->transform($data);
        }

        return $data;
    }
}
