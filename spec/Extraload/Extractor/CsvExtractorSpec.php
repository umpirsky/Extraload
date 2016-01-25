<?php

namespace spec\Extraload\Extractor;

use PhpSpec\ObjectBehavior;

class CsvExtractorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new \SplFileObject(__DIR__.'/../../Fixture/extract.csv'));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Extractor\CsvExtractor');
    }

    public function it_implements_extractor_interface()
    {
        $this->shouldImplement('Extraload\Extractor\ExtractorInterface');
    }

    public function it_iterates_over_csv_rows()
    {
        $this->extract()->shouldReturn(['a1', 'b1', 'c1']);
        $this->extract()->shouldReturn(['a2', 'b2', 'c2']);
        $this->extract()->shouldReturn(['a3', 'b3', 'c3']);
    }

    public function it_moves_over_csv_rows()
    {
        $this->current()->shouldReturn(['a1', 'b1', 'c1']);
        $this->next();
        $this->current()->shouldReturn(['a2', 'b2', 'c2']);
        $this->next();
        $this->current()->shouldReturn(['a3', 'b3', 'c3']);
    }

    public function it_rewinds()
    {
        $this->extract()->shouldReturn(['a1', 'b1', 'c1']);
        $this->rewind();
        $this->extract()->shouldReturn(['a1', 'b1', 'c1']);
    }
}
