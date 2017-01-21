<?php

namespace Pachico\SlimCorrelationId\Model;

abstract class AbstractId implements IdInterface
{

    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }


    /**
     * @{inheritdoc}
     */
    public function get()
    {
        return $this->id;
    }

    /**
     * @{inheritdoc}
     */
    public function __toString()
    {
        return $this->get();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->get();
    }
}
