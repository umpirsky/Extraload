<?php

namespace spec\Extraload\Loader\Doctrine;

use Doctrine\DBAL\Connection;
use PhpSpec\ObjectBehavior;

class DbalLoaderSpec extends ObjectBehavior
{
    public function let(Connection $connection)
    {
        $this->beConstructedWith($connection, 'data');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Extraload\Loader\Doctrine\DbalLoader');
    }

    public function it_implements_loader_interface()
    {
        $this->shouldImplement('Extraload\Loader\LoaderInterface');
    }

    public function it_loads_data_into_database_using_doctrine_dbal_connection(Connection $connection)
    {
        $connection->insert('data', ['a1', 'b1', 'c1'])->shouldBeCalled();

        $this->load(['a1', 'b1', 'c1']);
    }
}
