<?php

namespace Extraload\Transformer;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyTransformer implements TransformerInterface
{
    private $transformer;
    private $propertyAccessor;
    private $path;
    private $transformNullValues;

    public function __construct(
        TransformerInterface $transformer,
        PropertyAccessorInterface $propertyAccessor,
        $path,
        $transformNullValues = true
    ) {
        $this->transformer = $transformer;
        $this->propertyAccessor = $propertyAccessor;
        $this->path = $path;
        $this->transformNullValues = $transformNullValues;
    }

    public function transform($data)
    {
        $value = $this->propertyAccessor->getValue($data, $this->path);
        if (!$this->transformNullValues && null === $value) {
            return $data;
        }

        $this->propertyAccessor->setValue(
            $data,
            $this->path,
            $this->transformer->transform($value)
        );

        return $data;
    }
}
