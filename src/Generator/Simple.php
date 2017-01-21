<?php

namespace Pachico\SlimCorrelationId\Generator;

final class Simple implements IdInterface
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return substr(md5(uniqid('', true)), 0, 10);
    }
}
