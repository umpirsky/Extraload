<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\DBAL\DriverManager;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Extractor\CsvExtractor;
use Extraload\Transformer\NoopTransformer;
use Extraload\Transformer\CallbackTransformer;
use Extraload\Transformer\TransformerChain;
use Extraload\Loader\ConsoleLoader;
use Extraload\Loader\Doctrine\DbalLoader;

class DefaultPipelineContext extends BaseContext implements Context, SnippetAcceptingContext
{
    private $workingTable = 'book';
    private $workingFile;
    private $pipeline;
    private $output;
    private $connection;

    /**
     * @Given a file named :name with:
     */
    public function aFileNamedWith($name, PyStringNode $content)
    {
        $this->workingFile = $this->createFileFromStringNode($name, $content);
    }

    /**
     * @Given I create csv to console pipeline using :transformer transformer
     */
    public function iCreateCsvToConsolePipelineUsingTransformer($transformer)
    {
        return $this->pipeline = new DefaultPipeline(
            $this->createCsvExtractor(),
            $this->createTransformer($transformer),
            new ConsoleLoader(
                new Table($this->output = new BufferedOutput())
            )
        );
    }

    /**
     * @Given I create csv to database pipeline
     */
    public function iCreateCsvToDatabasePipeline()
    {
        return $this->pipeline = new DefaultPipeline(
            $this->createCsvExtractor(),
            $this->createTransformer('callable'),
            new DbalLoader(
                $this->getConnection(),
                $this->workingTable
            )
        );
    }

    /**
     * @Given I process it
     */
    public function iProcessIt()
    {
        $this->pipeline->process();
    }

    /**
     * @Then I should see in console:
     */
    public function iShouldSeeInConsole(PyStringNode $expected)
    {
        $expected = $this->stringNodeToString($expected);
        $actual = trim($this->output->fetch());

        PHPUnit_Framework_Assert::assertEquals($expected, $actual);
    }

    /**
     * @Then I should see in database:
     */
    public function iShouldSeeInDatabase(TableNode $table)
    {
        $actual = $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->workingTable)
            ->execute()
            ->fetchAll()
        ;

        foreach ($table->getHash() as $key => $expected) {
            PHPUnit_Framework_Assert::assertEquals($expected, $actual[$key]);
        }
    }

    private function createCsvExtractor()
    {
        return new CsvExtractor(new \SplFileObject($this->workingFile));
    }

    private function createTransformer($type)
    {
        switch ($type) {
            case 'callable':
                return new CallbackTransformer(function($data) {
                    return [
                        'isbn' => $data[0],
                        'title' => $data[1],
                        'author' => $data[2],
                    ];
                });

            case 'chain':
                return new TransformerChain([
                    new CallbackTransformer(function($data) {
                        unset($data[0]);

                        return $data;
                    }),
                    new CallbackTransformer(function($data) {
                        return [
                            'title' => $data[1],
                            'author' => $data[2],
                        ];
                    })
                ]);
        }

        throw new PendingException(sprintf('Implement %s transformer creator.', $type));
    }

    private function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = DriverManager::getConnection(['url' => 'sqlite:///:memory:']);
            $this->connection->exec(sprintf('CREATE TABLE %s(isbn, title, author)', $this->workingTable));
        }

        return $this->connection;
    }
}
