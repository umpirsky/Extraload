<?php

namespace Extraload\Transformer;

use Extraload\Transformer\TransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyTransformer implements TransformerInterface
{
    private $transformer;
    private $propertyAccessor;
    private $path;

    public function __construct(
        TransformerInterface $transformer,
        PropertyAccessorInterface $propertyAccessor,
        $path
    )
    {
        $this->transformer = $transformer;
        $this->propertyAccessor = $propertyAccessor;
        $this->path = $path;
    }

    public function transform($data)
    {
        $this->propertyAccessor->setValue(
            $data,
            $this->path,
            $this->transformer->transform(
                $this->propertyAccessor->getValue($data, $this->path)
            )
        );

        return $data;
    }
}
