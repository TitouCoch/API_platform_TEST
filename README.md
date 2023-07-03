[![forthebadge](https://forthebadge.com/images/badges/for-you.svg)](https://forthebadge.com)
# API Platform Test Runner  ✔️

This project aims to automate PHPUnit tests for Symfony API endpoints using the API provided by API Platform. It helps ensure the functionality and integrity of the endpoints by executing predefined tests.


### Built With

* [![Symfony][symfony.com]][symfony-url]
* [![Api-Platform][Api-Platform.com]][Api-Platform-url]
* [![PhpUnit][PhpUnit.com]][PhpUnit-url]

[symfony.com]: https://img.shields.io/badge/symfony-000000?style=for-the-badge&logo=symfony&logoColor=white
[symfony-url]: https://symfony.com/
[Api-Platform.com]: https://img.shields.io/badge/Api_Platform-0769AD?style=for-the-badge&logo=api&logoColor=white
[Api-Platform-url]: https://api-platform.com/
[PhpUnit.com]: https://img.shields.io/badge/PhpUnit-4A4A55?style=for-the-badge&logo=php&logoColor=white
[PhpUnit-url]: https://phpunit.de/

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
php bin/phpunit
````

---

>If you want coverage report you can add **xdebug** by running those commands

```shell 
>>Terminal
pecl install xdebug-3.1.3 

>>Or Dockerfile
RUN pecl install xdebug-3.1.3 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
````

>Then you can run your tests 

```shell
php -dxdebug.mode=coverage bin/phpunit --coverage-html ./CoverageReport
//or
php bin/phpunit --coverage-text 

````
This command will run the PHPUnit tests and generate a coverage report in the CoverageReport folder. You can open the generated HTML report in your web browser to analyze the test coverage.

---
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

- [x] You run your test suites using the command

```shell
php bin/phpunit --list-suites
php bin/phpunit --testsuite api
````

Errors on tests are returned to the errors.json file 



# Explanation of functions for each file

```diff
- JsonTest.php 
```

*verifyPath( string $path ) : string*

Description : Function that checks the path entered and if needed get "{uuid}, {id}, /me" from the fixtures

Input : string //Example /api/test/{uuid}

OutPut : string //Example /api/test/1124d9e8-6266-4bcf-8035-37a02ba75c69

---

*verifyMethod( string $method ) : void*

Description : Function that converts the method and checks if it is in the list of accepted methods

Input : string //Example get

OutPut : //Example Change method variable to : GET

---

*getFilters( string $path, string $method ) : array|null*

Description : Function checks if there are parameters in the component. If parameters are present, it checks if they are required. If a parameter is required, a new filter is assigned. If a parameter is not required, an empty array is returned.

Input : string, string //Example /api/test, GET

OutPut : array //Example [] or [deleteAt=false]

---

*getData( string $component ) : void*

Description: The function checks if the attribute is valid to be in the data, then based on its type attributes a default value

Input : string //Example Test.jsonld-Test_write"

OutPut : array //Example Change data variable to : [] or [code=1,...]

---

*getFileName( string $path ) : string*

Description: Converts the path to a path that can be a readable and easily understood filename

Input : string //Example /api/test

OutPut : string //Example _1_Test

---

*testAPI() : void*

Description: Main test function that called many functions to get the path, method, response code, data, filter. Then call the API to test these metadata

Input : //

OutPut : //


```diff
- MethodTest.php 
```

*setUp() : void*

Description: Initialize the application kernel before running the tests, and get the token so that you can call the API

Input : //

OutPut : //

---

*clearLogTest() : void*

Description: Empties the log file ('Test' in the function name so that it is called automatically when the tests are started)

Input : //

OutPut : //

---

*getToken() : string|null*

Description: Check if the token exists, otherwise call the login path with the credentials and get the token.

Input : //

OutPut : string //Example aexs23KjdijsEDSJI342CnjdRj...

---

*getDataFixture( string $pathCut, string $key, array $filters, ?string $path = null ): string|null*

Description: Recovers the last data key from the API (id, uuid). Return an error if there is no data in the database or if the key does not match an existing attribute 

Input : string, string, array, ?string //Example /api/test, uuid, [], null

OutPut : string //Example 1124d9e8-6266-4bcf-8035-37a02ba75c69

---

*getDefaultValue( string $definition ): bool|object|array|int|string|null*

Description: Attributes a default value based on the data type 

Input : string //Example                         "schema": {
                            "type": "string"
                        }

OutPut : string //Example test

---

*matchFilter( string $name ): ?string*

Description: Attribute a value to the filter entered, if it does not exist you can add it directly in the function

Input : string //Example deletedAt

OutPut : string //Example false

---

*tranformPath( string $path ): string|null*

Description: Transforms path to a valid attribut name 

Input : string //Example /api/local_countries

OutPut : string //Example localCountry

---

*getIriReference( string $property ): bool*

Description: Adds the last attribute data if it exists as a path in the API

Input : string //Example localCountry

OutPut : bool //Example false

---

*reachComponent( string $component ): array|string|null*

Description: Try to find the best component to build the object using the list (the list can be modified as needed)

Input : string //Example Picture.jsonld_read

OutPut : bool //Example Picture.jsonld_write

---

*throwError( string $path, string $message ): void*

Description: Write in the log file the errors passed as parameter

Input : string, string //Example /api/test/me, 'Token invalid'

OutPut : //

---
