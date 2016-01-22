<?php

namespace Extraload\Extractor;

interface ExtractorInterface extends \Iterator
{
    public function extract();
}
