#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\Element;
use Extraload\Pipeline\DefaultPipeline;
use Extraload\Extractor\ExtractorInterface;
use Extraload\Transformer\TransformerInterface;
use Extraload\Transformer\TransformerChain;
use Extraload\Transformer\PropertyTransformer;
use Extraload\Transformer\CallbackTransformer;
use Extraload\Loader\ConsoleLoader;
use Extraload\Events;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

class Extractor implements ExtractorInterface
{
    private $mink;
    private $urls = [];
    private $index = 0;

    public function __construct(Mink $mink)
    {
        $this->mink = $mink;

        $this->getSession()->visit('http://www.amazon.com/gp/goldbox');

        foreach ($this->getSession()->getPage()->findAll('css', '#widgetContent div.dealTile > a') as $element) {
            $this->urls[] = $element->getAttribute('href');
        }
    }

    public function extract()
    {
        $data = $this->current();

        $this->next();

        return $data;
    }

    public function current()
    {
        if (!$this->valid()) {
            return;
        }

        $this->getSession()->visit($this->urls[$this->key()]);

        return $this->getSession()->getPage();
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        ++$this->index;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function valid()
    {
        return isset($this->urls[$this->key()]);
    }

    private function getSession()
    {
        return $this->mink->getSession('selenium2');
    }
}

class DocumentToElementTransformer implements TransformerInterface
{
    public function transform($data)
    {
        if (!$data instanceof DocumentElement) {
            throw new \InvalidArgumentException('Can transform only DocumentElement.');
        }

        return [
            'title' => $data->findById('title'),
            'price' => $data->findById('priceblock_dealprice'),
        ];
    }
}

class ElementToStringTransformer implements TransformerInterface
{
    public function transform($data)
    {
        foreach ($data as $key => $element) {
            if (null === $element) {
                return;
            }

            if (!$element instanceof Element) {
                throw new \InvalidArgumentException('Can transform only Element.');
            }

            $data[$key] = $element->getText();
        }

        return $data;
    }
}

function truncate($value)
{
    $length = 30;

    if (strlen($value) > $length) {
        if (false !== ($breakpoint = strpos($value, ' ', $length))) {
            $length = $breakpoint;
        }

        return rtrim(substr($value, 0, $length)).'...';
    }

    return $value;
}

$output = new ConsoleOutput();

$dispatcher = new EventDispatcher();
$dispatcher->addListener(Events::LOAD, function (GenericEvent $event) use ($output) {
    $output->writeln(sprintf('Loading <info>%s</info>', $event->getSubject()['title']));
});

(new DefaultPipeline(
    new Extractor(new Mink([
        'selenium2' => new Session(new Selenium2Driver()),
    ])),
    new TransformerChain([
        new DocumentToElementTransformer(),
        new ElementToStringTransformer(),
        new PropertyTransformer(
            new CallbackTransformer('truncate'),
            PropertyAccess::createPropertyAccessor(),
            '[title]',
            false
        ),
    ]),
    new ConsoleLoader(new Table($output)),
    $dispatcher
))->process();
