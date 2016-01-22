<?php

namespace Extraload\Extractor;

class CsvExtractor implements ExtractorInterface
{
    private $file;

    public function __construct(\SplFileObject $file, $delimiter = ',', $enclosure = '"')
    {
        $this->file = $file;
        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD | \SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_CSV);
        $this->file->setCsvControl($delimiter, $enclosure);
    }

    public function extract()
    {
        if ($this->file->eof()) {
            return;
        }

        $data = $this->current();

        $this->next();

        return $data;
    }

    public function current()
    {
        return $this->file->current();
    }

    public function key()
    {
        return $this->file->key();
    }

    public function next()
    {
        return $this->file->next();
    }

    public function rewind()
    {
        return $this->file->rewind();
    }

    public function valid()
    {
        return $this->file->valid();
    }
}
