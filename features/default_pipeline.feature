Feature: Default pipeline
    In order to run sequentiel ETL process
    As ETL developer
    I need to be able to process default ETL pipeline

    Background:
        Given a file named "books.csv" with:
            """
            "99921-58-10-7", "Divine Comedy", "Dante Alighieri"
            "9971-5-0210-0", "A Tale of Two Cities", "Charles Dickens"
            "960-425-059-0", "The Lord of the Rings", "J. R. R. Tolkien"
            "80-902734-1-6", "And Then There Were None", "Agatha Christie"
            """

    Scenario: Dump CSV file to console table
        Given I create csv to console pipeline
          And I process it
         Then I should see in console:
            """
            +---------------+-----------------------+------------------+
            | 99921-58-10-7 | Divine Comedy         | Dante Alighieri  |
            | 9971-5-0210-0 | A Tale of Two Cities  | Charles Dickens  |
            | 960-425-059-0 | The Lord of the Rings | J. R. R. Tolkien |
            +---------------+-----------------------+------------------+
            """

    Scenario: Import CSV file into database
        Given I create csv to database pipeline
          And I process it
         Then I should see in database:
            | isbn          | title                 | author           |
            | 99921-58-10-7 | Divine Comedy         | Dante Alighieri  |
            | 9971-5-0210-0 | A Tale of Two Cities  | Charles Dickens  |
            | 960-425-059-0 | The Lord of the Rings | J. R. R. Tolkien |
