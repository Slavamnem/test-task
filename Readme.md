Documentation

1) Requirements:
    - php 8.1
    - composer
2) Run program:
    - composer install
    - php script.php input.csv

3) Run test:
    - php vendor/bin/phpunit

4) Short program description:
The main function of the program is that it parses a csv file with a list of users transactions
and for each transaction, taking into account the previous transactions of the user, 
calculates the commission according to the rules.
Each rule is presented as a separate link in the chain of calculators.
This makes it easy to modify the commission calculation logic.
The command is easily extensible for new currencies, changing the default currency, for new types of users,
new types of transactions and new commission calculation rules.
It is also possible to add alternative ways to read transactions and mathematical calculations.