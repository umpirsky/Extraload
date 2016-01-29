<?php

namespace spec\Extraload\Extractor;

use PhpSpec\ObjectBehavior;

class CsvExtractorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new \SplFileObject(__DIR__.'/../../../fixtures/books.csv'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Extractor\CsvExtractor');
    }

    function it_implements_extractor_interface()
    {
        $this->shouldImplement('Extraload\Extractor\ExtractorInterface');
    }

    function it_iterates_over_csv_rows()
    {
        $this->extract()->shouldReturn(['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri']);
        $this->extract()->shouldReturn(['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens']);
        $this->extract()->shouldReturn(['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien']);
        $this->extract()->shouldReturn(['80-902734-1-6', 'And Then There Were None', 'Agatha Christie']);
    }

    function it_moves_over_csv_rows()
    {
        $this->current()->shouldReturn(['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri']);
        $this->next();
        $this->current()->shouldReturn(['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens']);
        $this->next();
        $this->current()->shouldReturn(['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien']);
    }

    function it_rewinds()
    {
        $this->extract()->shouldReturn(['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri']);
        $this->rewind();
        $this->extract()->shouldReturn(['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri']);
    }
}
