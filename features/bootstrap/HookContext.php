<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class HookContext extends BaseContext implements Context, SnippetAcceptingContext
{
    /**
     * @BeforeScenario
     */
    public function initialize()
    {
        self::$workingDirectory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'etl'.DIRECTORY_SEPARATOR;
    }

    /**
     * @AfterScenario
     */
    public function cleanup()
    {
        $this->clearDirectory(self::$workingDirectory);
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
