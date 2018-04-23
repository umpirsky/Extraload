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


## Examples

### Dumping CSV data into the console

Input data is given in csv format:
```csv
"99921-58-10-7", "Divine Comedy", "Dante Alighieri"
"9971-5-0210-0", "A Tale of Two Cities", "Charles Dickens"
"960-425-059-0", "The Lord of the Rings", "J. R. R. Tolkien"
"80-902734-1-6", "And Then There Were None", "Agatha Christie"
```
With:
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
It can be dumped as table to console:
```
+---------------+--------------------------+------------------+
| 99921-58-10-7 | Divine Comedy            | Dante Alighieri  |
| 9971-5-0210-0 | A Tale of Two Cities     | Charles Dickens  |
| 960-425-059-0 | The Lord of the Rings    | J. R. R. Tolkien |
| 80-902734-1-6 | And Then There Were None | Agatha Christie  |
+---------------+--------------------------+------------------+
```
In this example `NoopTransformer` is used, but various transformations can be applied. Transformers can also be chained using `TransformerChain`.

### Dumping a Doctrine query into the console

First of all make sure to load the fixtures into a database -- this example works with MySQL:

    mysql> source /home/standard/projects/Extraload/fixtures/mysql/books.sql

So the following code:

```php
(new DefaultPipeline(
    new QueryExtractor($conn, 'SELECT * FROM books'),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
```

Will dump these results to the console:

    +---------------+--------------------------+------------------+
    | 99921-58-10-7 | Divine Comedy            | Dante Alighieri  |
    | 9781847493583 | La Vita Nuova            | Dante Alighieri  |
    | 9971-5-0210-0 | A Tale of Two Cities     | Charles Dickens  |
    | 960-425-059-0 | The Lord of the Rings    | J. R. R. Tolkien |
    | 80-902734-1-6 | And Then There Were None | Agatha Christie  |
    +---------------+--------------------------+------------------+

### Dumping a Doctrine prepared query into the console

The following code:

```php
// ...

$sql = "SELECT * FROM books WHERE author = :author";
$values = [
    [
        'parameter' => ':author',
        'value' => 'Dante Alighieri',
        'data_type' => PDO::PARAM_STR // optional
    ]
];

(new DefaultPipeline(
    new QueryExtractor($conn, $sql, $values),
    new NoopTransformer(),
    new ConsoleLoader(
        new Table($output = new ConsoleOutput())
    )
))->process();
```

Will dump these results to the console:

    +---------------+---------------+-----------------+
    | 99921-58-10-7 | Divine Comedy | Dante Alighieri |
    | 9781847493583 | La Vita Nuova | Dante Alighieri |
    +---------------+---------------+-----------------+

### Dumping a Doctrine query into a table

The following code:

```php
// ...

(new DefaultPipeline(
    new QueryExtractor($conn, 'SELECT * FROM books'),
    new NoopTransformer(),
    new DbalLoader($conn, 'my_books')
))->process();
```

Will dump the results into the `my_books` table:

    mysql> select * from my_books;
    +----------------+--------------------------+----------------------------+
    | isbn           | title                    | author                     |
    +----------------+--------------------------+----------------------------+
    | 9781503262140  | Faust                    | Johann Wolfgang von Goethe |
    | 978-0156949606 | The Waves                | Virgina Woolf              |
    | 99921-58-10-7  | Divine Comedy            | Dante Alighieri            |
    | 9781847493583  | La Vita Nuova            | Dante Alighieri            |
    | 9971-5-0210-0  | A Tale of Two Cities     | Charles Dickens            |
    | 960-425-059-0  | The Lord of the Rings    | J. R. R. Tolkien           |
    | 80-902734-1-6  | And Then There Were None | Agatha Christie            |
    +----------------+--------------------------+----------------------------+
    7 rows in set (0.00 sec)

See more [examples](https://github.com/umpirsky/Extraload/tree/master/examples).

## 2. Inspiration

Inspired by [php-etl](https://github.com/docteurklein/php-etl) and [petl](https://github.com/alimanfoo/petl).
