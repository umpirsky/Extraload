<?php

namespace spec\Extraload\Loader\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\DBAL\Connection;

class DbalLoaderSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection, 'data');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\Doctrine\DbalLoader');
    }

    function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    function it_loads_data_into_database_using_doctrine_dbal_connection(Connection $connection)
    {
        $connection->insert('data', ['a1', 'b1', 'c1'])->shouldBeCalled();

        $this->load(['a1', 'b1', 'c1']);
    }
}
