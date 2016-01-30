#!/usr/bin/env php
<?php

require __DIR__.'/lib.php';

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\Selenium2Driver;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Transformer\TransformerChain;
use Extraload\Transformer\PropertyTransformer;
use Extraload\Transformer\CallbackTransformer;
use Extraload\Loader\ConsoleLoader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\PropertyAccess\PropertyAccess;

(new DefaultPipeline(
    new AmazonExtractor(new Mink([
        'selenium2' => new Session(new Selenium2Driver()),
    ])),
    new TransformerChain([
        new DocumentToElementTransformer(),
        new ElementToStringTransformer(),
        new PropertyTransformer(
            new CallbackTransformer('truncate'),
            PropertyAccess::createPropertyAccessor(),
            '[title]'
        ),
    ]),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
