<?php

namespace Pachico\SlimCorrelationId\Model;

class CorrelationIdTest extends \PHPUnit_Framework_TestCase
{

    const TEST_ID = 'abc123';

    public function testIdGetters()
    {
        // Arrange
        $sut = new CorrelationId(static::TEST_ID);
        // Assert
        $this->assertSame(static::TEST_ID, $sut->get());
        $this->assertSame(static::TEST_ID, (string) $sut);
        $this->assertSame(json_encode(static::TEST_ID), json_encode($sut));
    }

    public function testCreateReturnsNewIdUsingGenerator()
    {
        // Arrange
        $generatorMock = $this->getMockBuilder('Pachico\SlimCorrelationId\Generator\IdInterface')->getMock();
        $generatorMock->expects($this->once())->method('create')->will($this->returnValue(static::TEST_ID));
        // Act
        $newId = CorrelationId::create($generatorMock);
        // Assert
        $this->assertSame(static::TEST_ID, $newId->get());
    }
}
