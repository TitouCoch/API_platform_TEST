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

2. Execute the following command:

```shell
php bin/phpunit --coverage-html ./CoverageReport
````

This command will run the PHPUnit tests and generate a coverage report in the CoverageReport folder. You can open the generated HTML report in your web browser to analyze the test coverage.

## Here is the result 

<a href="https://imgur.com/CTnnKC4"><img src="https://i.imgur.com/CTnnKC4.png" title="source: imgur.com" /></a>
