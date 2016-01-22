<?php

namespace Extraload\Transformer;

class NoopTransformer implements TransformerInterface
{
    public function transform($data)
    {
        return $data;
    }
}
