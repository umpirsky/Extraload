<?php

namespace Extraload\Transformer;

class MapFieldsTransformer implements TransformerInterface
{
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function transform($data)
    {
        $transformation = [];
        foreach ($data as $key => $val) {
            if (array_key_exists($key, $this->fields)) {
                $transformation[$this->fields[$key]] = $val;
            }
        }

        return $transformation;
    }
}
