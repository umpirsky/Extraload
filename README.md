<h3 align="center">
    <a href="https://github.com/umpirsky">
        <img src="https://farm2.staticflickr.com/1709/25098526884_ae4d50465f_o_d.png" />
    </a>
</h3>
<p align="center">
  <a href="https://github.com/umpirsky/Symfony-Upgrade-Fixer">symfony upgrade fixer</a> &bull;
  <a href="https://github.com/umpirsky/Twig-Gettext-Extractor">twig gettext extractor</a> &bull;
  <a href="https://github.com/umpirsky/wisdom">wisdom</a> &bull;
  <a href="https://github.com/umpirsky/centipede">centipede</a> &bull;
  <a href="https://github.com/umpirsky/PermissionsHandler">permissions handler</a> &bull;
  <b>extraload</b> &bull;
  <a href="https://github.com/umpirsky/Gravatar">gravatar</a> &bull;
  <a href="https://github.com/umpirsky/locurro">locurro</a> &bull;
  <a href="https://github.com/umpirsky/country-list">country list</a> &bull;
  <a href="https://github.com/umpirsky/Transliterator">transliterator</a>
</p>

# Extraload [![Build Status](https://travis-ci.org/umpirsky/Extraload.svg?branch=master)](https://travis-ci.org/umpirsky/Extraload) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/umpirsky/Extraload/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/umpirsky/Extraload/?branch=master)

Powerful ETL library.

## Example

Dumping csv data to console:

```php
(new DefaultPipeline(
    new CsvExtractor(
        new \SplFileObject('books.csv')
    ),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table(new ConsoleOutput())
    )
))->process();
```

See more [examples](https://github.com/umpirsky/Extraload/tree/master/examples).

## Inspiration

Inspired by [php-etl](https://github.com/docteurklein/php-etl) and [petl](https://github.com/alimanfoo/petl).
