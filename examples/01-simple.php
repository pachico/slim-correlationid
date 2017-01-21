<?php

/**
 * This example will just resolve the current correlation id from the request header.
 * If not present, then it will create one and append it to the request and the response.
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();
$app->add(new Middleware\CorrelationId());
