<?php

namespace spec\Extraload\Loader;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Helper\Table;

class ConsoleLoaderSpec extends ObjectBehavior
{
    public function let(Table $table)
    {
        $this->beConstructedWith($table);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\ConsoleLoader');
    }

    public function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    public function it_loads_data_in_console_using_table_helper(Table $table)
    {
        $table->addRow(['a1', 'b1', 'c1'])->shouldBeCalled();

        $this->load(['a1', 'b1', 'c1']);
    }

    public function it_renders_data_in_console_on_flush(Table $table)
    {
        $table->render()->shouldBeCalled();

        $this->flush();
    }
}
