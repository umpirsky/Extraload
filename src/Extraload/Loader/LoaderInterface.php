<?php

namespace Extraload\Loader;

interface LoaderInterface
{
    public function load($data);

    public function flush();
}
