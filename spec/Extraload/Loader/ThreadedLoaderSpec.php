<?php

namespace spec\Extraload\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThreadedLoaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\ThreadedLoader');
    }

    function it_extends_threaded()
    {
        $this->shouldHaveType('Threaded');
    }

    function it_runs_loader_in_a_separate_thread(LoaderInterface $loader)
    {
        $loader->load(Argument::any())->shouldBeCalled();

        $this->run();
    }
}
