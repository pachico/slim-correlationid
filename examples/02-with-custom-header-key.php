<?php

/**
 * It is also possible to set which key in the request, it will try to resolve it from
 */
use \Pachico\SlimCorrelationId\Middleware;

$app = new \Slim\App();
$app->add(new Middleware\CorrelationId([
    'header_key' => 'X-CustomCorrelation-Id'
]));
