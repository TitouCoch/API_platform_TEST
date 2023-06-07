[![forthebadge](https://forthebadge.com/images/badges/for-you.svg)](https://forthebadge.com)
# API Platform Test Runner  

This project aims to automate PHPUnit tests for Symfony API endpoints using the API provided by API Platform. It helps ensure the functionality and integrity of the endpoints by executing predefined tests.

## Installation

1. Copy the entire project into the `tests` folder of your Symfony API project.

2. Open the `methodeTest.php` file located in the project directory.

3. Configure the necessary parameters for your tests in the `methodeTest.php` file (login path, filter, credentials).

4. Make sure you have created the required data fixtures for all the endpoints you wish to test. These fixtures will provide the necessary data for the test cases.

## Usage

To run the test suite, follow these steps:

1. Open your terminal and navigate to the root directory of your Symfony project.

2. Export the API with the following command

```shell
php bin/console api:openapi:export -o ...path/openapi.json
````

3. Execute the following command:

```shell
php -dxdebug.mode=coverage bin/phpunit --coverage-html ./CoverageReport
//or
php bin/phpunit --coverage-text 
````

This command will run the PHPUnit tests and generate a coverage report in the CoverageReport folder. You can open the generated HTML report in your web browser to analyze the test coverage.

## Here is the result 

<a href="https://imgur.com/CTnnKC4"><img src="https://i.imgur.com/CTnnKC4.png" title="source: imgur.com" /></a>
--
<a href="https://imgur.com/5JBAT56"><img src="https://i.imgur.com/5JBAT56.png" title="source: imgur.com" style="width:30%"/></a>

The program will automatically generate the test suite with your path 

```php
    <testsuites>
        <testsuite name="unit">
            <directory>tests</directory>
        </testsuite>

        <testsuite name="api">
            <directory>tests/API</directory>
        </testsuite>

        <testsuite name="endpoint_file">
            <directory>tests/API/EndPoint</directory>
        </testsuite>
    </testsuites>
````

You run your test suites using the command

```shell
php bin/phpunit --list-suites
php bin/phpunit --testsuite api
````

Errors on tests are returned to the errors.json file
