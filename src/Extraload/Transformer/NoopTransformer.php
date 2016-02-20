<?php

namespace Extraload\Transformer;

class NoopTransformer implements TransformerInterface
{
    public function transform($data = null)
    {
        return $data;
    }
}
