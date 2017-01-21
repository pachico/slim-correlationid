<?php

namespace Pachico\SlimCorrelationId\Generator;

interface IdInterface
{
    /**
     * @return string Returns a newly generated Id
     */
    public function create();
}
