
=======
Molajo Log API
=======

[![Build Status](https://travis-ci.org/Molajo/Log.png?branch=master)](https://travis-ci.org/Molajo/Log)

Simple, clean API for PHP applications to use with logging.

## At a glance ... ##
First, the application connects to a Log Handler.
Then, the application can use that connection to perform log operations.

### Example ###
```php

    // Connect to Log Handler
    use Molajo\Log\Connection;
    $adapter = new Connection('Memory');

    // Use connection for log operations
    $adapter->log($level, $message, $context = array());

    // Use connection to retrieve the entire log
    $log = $adapter->get();

```

### Requirements and Compliance ###
 * PHP framework independent, no dependencies
 * Requires PHP 5.3, or above
 * [Semantic Versioning](http://semver.org/)
 * Compliant with:
    * [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) Namespacing
    * [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) Coding Standards
    * [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) Logger Interface
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * Listed on [Packagist] (http://packagist.org) and installed using [Composer] (http://getcomposer.org/)
 * Use github to submit [pull requests](https://github.com/Molajo/Log/pulls) and [features](https://github.com/Molajo/Log/issues)

## Install using Composer from Packagist ##

### Step 1: Install composer in your project ###

```php
    curl -s https://getcomposer.org/installer | php
```

### Step 2: Create a **composer.json** file in your project root ###

```php
{
    "require": {
        "Molajo/Log": "1.*"
    }
}
```

### Step 3: Install via composer ###

```php
    php composer.phar install
```

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen><br />
See also the list of [contributors](https://github.com/Molajo/Log/contributors) participating in this project.

License
-------

**Molajo Log** is licensed under the MIT License - see the `LICENSE` file for details
