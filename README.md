# slim-correlationid

[![Build Status](https://travis-ci.org/pachico/slim-correlationid.svg?branch=master)](https://travis-ci.org/pachico/slim-correlationid) [![codecov](https://codecov.io/gh/pachico/slim-correlationid/branch/master/graph/badge.svg)](https://codecov.io/gh/pachico/slim-correlationid)


Resolves and propagates correlation ids. If none, it will create one. If callable is provided, it will invoke it as soon as it resolves/creates it.

Especially useful for microservices platforms.

It has been designed with [Slim framework](https://www.slimframework.com/) in mind but it works with any middleware signature like `fn(request, response, next): response`.

Refer to [Slim documentation](https://www.slimframework.com/docs/concepts/middleware.html) for details about middlewares.

## Requirements
This library currently supports **PHP >= 5.4**.

## Install

Via Composer

```bash
$ composer require pachico/slim-correlationid
```

## Usage

### Simple

```php
<?php
/**
 * This example will just resolve the current correlation id from the request header.
 * If not present, then it will create one and append it to the request and the response.
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();
$app->add(new Middleware\CorrelationId());

```

### With custom header key
```php
<?php

/**
 * It is also possible to set which key in the request, it will try to resolve it from
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();
$app->add(new Middleware\CorrelationId([
    'header_key' => 'X-CustomCorrelation-Id'
]));

```

### With custom callable
```php
<?php

/**
 * In this case, we pass a callable that will be executed right after the correlation id
 * is resolved.
 * It might be useful for registering it to dependency containers, or instantiate objects
 * with the id (loggers, http clients, etc)
 */
use \Pachico\SlimCorrelationId\Middleware;
use \Pachico\SlimCorrelationId\Model;

$app = new \Slim\App();
$dummyObject = (object) [
        'correlationIdObject' => null
];
$customCallable = function (Model\CorrelationId $correlationid) use ($dummyObject) {
    $dummyObject->correlationIdObject = $correlationid;
};

$app->add(new Middleware\CorrelationId([], $customCallable));

```

### With custom id generator
```php
<?php

/**
 * How ids are generated can also be customized by injecting a custom Id generator, 
 * as long as it implements the IdGenerator interface.
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();

$app->add(new Middleware\CorrelationId([], null, new MyCustomIdGenerator()));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email pachicodev@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.