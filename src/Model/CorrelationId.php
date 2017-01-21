<?php

namespace Pachico\SlimCorrelationId\Model;

use \Pachico\SlimCorrelationId\Generator;

class CorrelationId extends AbstractId
{

    const DEFAULT_HEADER_KEY = 'X-Correlation-Id';
    const DEFAULT_KEY = 'correlation_id';

    /**
     * @{inheritdoc}
     */
    public static function create(Generator\IdInterface $generator)
    {
        return new self($generator->create());
    }
}
