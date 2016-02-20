<?php

namespace Extraload\Transformer;

class CallbackTransformer implements TransformerInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function transform($data = null)
    {
        return call_user_func($this->callback, $data);
    }
}
