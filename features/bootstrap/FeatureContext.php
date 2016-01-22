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

class FeatureContext implements Context, SnippetAcceptingContext
{
    private $workingDir;
    private $workingFile;
    private $pipeline;
    private $output;

    /**
     * @BeforeScenario
     */
    public function initialize()
    {
        $this->workingDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'etl'.DIRECTORY_SEPARATOR;
    }

    /**
     * @AfterScenario
     */
    public function cleanup()
    {
        $this->clearDirectory($this->workingDir);
    }

    /**
     * @Given a file named :name with:
     */
    public function aFileNamedWith($name, PyStringNode $content)
    {
        $this->createFile(
            $this->workingFile = $this->workingDir.$name,
            $this->stringNodeToString($content)
        );
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

    private function stringNodeToString(PyStringNode $string)
    {
        return strtr((string) $string, array("'''" => '"""'));
    }

    private function createFile($name, $content)
    {
        $this->createDirectory(dirname($name));

        file_put_contents($name, $content);
    }

    private function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function clearDirectory($path)
    {
        $files = scandir($path);
        array_shift($files);
        array_shift($files);

        foreach ($files as $file) {
            $file = $path.DIRECTORY_SEPARATOR.$file;
            if (is_dir($file)) {
                $this->clearDirectory($file);
            } else {
                unlink($file);
            }
        }

        rmdir($path);
    }
}
