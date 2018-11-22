<?php

namespace Extraload\Loader;

interface LoaderInterface
{
    public function load($data = null);
    public function flush();
}
