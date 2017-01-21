<?php

namespace Pachico\SlimCorrelationId\Generator;

class SimpleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Simple
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new Simple();
    }

    public function testCreateReturnsString()
    {
        // Act
        $id = $this->sut->create();
        // Assert
        $this->assertInternalType('string', $id);
        $this->assertSame(10, strlen($id));
    }

    public function testIdIsAlwaysUnique()
    {
        // Arrange
        $ids = [];
        for ($index = 0; $index < 100; $index++) {
            $ids[] = $this->sut->create();
        }
        $uniqueIds = array_unique($ids);
        // Assert
        $this->assertSame(100, count($uniqueIds));
    }
}
