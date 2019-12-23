<?php

namespace Extraload\Transformer;

use Symfony\Component\PropertyAccess\PropertyAccess;

class MapFieldsTransformer implements TransformerInterface
{
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function transform($data)
    {
        $result = [];

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($data as $key => $val) {
            if (array_key_exists($key, $this->fields)) {
                $propertyAccessor->setValue($result, "[{$this->fields[$key]}]", $val);
            }
        }

        return $result;
    }
}
