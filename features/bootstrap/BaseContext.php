<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

class BaseContext implements Context
{
    protected static $workingDirectory;

    protected function createFile($name, $content)
    {
        $path = self::$workingDirectory.$name;

        $this->createDirectory(dirname($path));

        file_put_contents($path, $content);

        return $path;
    }

    protected function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    protected function stringNodeToString(PyStringNode $string)
    {
        return strtr((string) $string, array("'''" => '"""'));
    }
}
