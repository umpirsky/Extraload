<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Extractor\CsvExtractor;
use Extraload\Transformer\NoopTransformer;
use Extraload\Loader\ConsoleLoader;

class FeatureContext extends BaseContext implements Context, SnippetAcceptingContext
{
    private $workingFile;
    private $pipeline;
    private $output;

    /**
     * @Given a file named :name with:
     */
    public function aFileNamedWith($name, PyStringNode $content)
    {
        $this->workingFile = $this->createFileFromStringNode($name, $content);
    }

    /**
     * @Given I create csv to console pipeline
     */
    public function iCreateCsvToConsolePipeline()
    {
        $this->pipeline = new DefaultPipeline(
            new CsvExtractor(new \SplFileObject($this->workingFile)),
            new NoopTransformer(),
            new ConsoleLoader(new Table($this->output = new BufferedOutput()))
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
}
