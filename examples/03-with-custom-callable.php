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
