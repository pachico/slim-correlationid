<?php

namespace Pachico\SlimCorrelationId\Model;

use \Pachico\SlimCorrelationId\Generator;

interface IdInterface extends \JsonSerializable
{
    /**
     * @param Generator\IdInterface $generator
     *
     * @return IdInterface
     */
    public static function create(Generator\IdInterface $generator);

    /**
     * @return string
     */
    public function __toString();
    
    /**
     * @return string
     */
    public function get();
}
