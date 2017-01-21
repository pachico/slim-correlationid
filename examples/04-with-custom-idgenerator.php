<?php

/**
 * How ids are generated can also be customized by injecting a custom Id generator,
 * as long as it implements the IdGenerator interface.
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();

$app->add(new Middleware\CorrelationId([], null, new MyCustomIdGenerator()));
