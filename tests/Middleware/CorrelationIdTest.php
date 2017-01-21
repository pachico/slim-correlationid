<?php

namespace Pachico\SlimCorrelationId\Middleware;

use \Psr\Http\Message;
use \Pachico\SlimCorrelationId\Model;

class CorrelationIdTest extends \PHPUnit_Framework_TestCase
{

    const TEST_ID = 'abc123';

    /**
     * @var CorrelationId
     */
    private $sut;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $generatorMock;

    /**
     * @var callback|null
     */
    private $callback;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;

    public function setUp()
    {
        $this->generatorMock = $this->getMockBuilder('\Pachico\SlimCorrelationId\Generator\IdInterface')->getMock();
        $this->requestMock = $this->getMockBuilder('\Psr\Http\Message\RequestInterface')->getMock();
        $this->responseMock = $this->getMockBuilder('\Psr\Http\Message\ResponseInterface')->getMock();
        $this->sut = new CorrelationId([], $this->callback, $this->generatorMock);
    }

    /**
     * @return callable
     */
    private function getFakeNextCallable()
    {
        return function (Message\RequestInterface $request, Message\ResponseInterface $response) {
            return $response;
        };
    }

    public function testInvokeReturnsResponse()
    {
        // Arrange
        $this->generatorMock->expects($this->once())->method('create')->will($this->returnValue(static::TEST_ID));
        $this->requestMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        $this->responseMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        // Act
        $response = call_user_func_array($this->sut, [
            $this->requestMock,
            $this->responseMock,
            $this->getFakeNextCallable()
        ]);
        // Assert
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
    }
    
    public function testInvokeWillGenerateNewId()
    {
        // Arrange
        $this->generatorMock->expects($this->once())->method('create')->will($this->returnValue(static::TEST_ID));
        $this->requestMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        $this->requestMock->expects($this->once())->method('getHeaderLine')->will($this->returnValue(null));
        $this->responseMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        // Act
        $response = call_user_func_array($this->sut, [
            $this->requestMock,
            $this->responseMock,
            $this->getFakeNextCallable()
        ]);
        // Assert
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
    }
    
    public function testInvokeFetchesIdFromRequest()
    {
        // Arrange
        $this->generatorMock->expects($this->never())->method('create')->will($this->returnValue(static::TEST_ID));
        $this->requestMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        $this->requestMock->expects($this->once())->method('getHeaderLine')
            ->will($this->returnValue(static::TEST_ID));
        $this->responseMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        // Act
        $response = call_user_func_array($this->sut, [
            $this->requestMock,
            $this->responseMock,
            $this->getFakeNextCallable()
        ]);
        // Assert
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
    }
    
    public function testInvokeFetchesIdFromCustomHeaderKey()
    {
        // Arrange
        $customHeaderKey = 'X-CustomKey-Id';
        $this->sut = new CorrelationId(
            [
                'header_key' => $customHeaderKey
            ],
            $this->callback,
            $this->generatorMock
        );
        $this->generatorMock->expects($this->never())->method('create')->will($this->returnValue(static::TEST_ID));
        $this->requestMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        $this->requestMock->expects($this->once())->method('getHeaderLine')->with($customHeaderKey)
            ->will($this->returnValue(static::TEST_ID));
        $this->responseMock->expects($this->once())->method('withHeader')->with($customHeaderKey)
            ->will($this->returnSelf());
        // Act
        $response = call_user_func_array($this->sut, [
            $this->requestMock,
            $this->responseMock,
            $this->getFakeNextCallable()
        ]);
        // Assert
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
    }
    
    public function testInvokeWillInvokeCallableWithRequestId()
    {
        // Arrange
        $dummy = (object) [
           'foo' => null
        ];
        $this->callback = function (Model\CorrelationId $correlationid) use ($dummy) {
            $dummy->foo = $correlationid->get();
        };
        
        $this->sut = new CorrelationId([], $this->callback, $this->generatorMock);
        $this->generatorMock->expects($this->never())->method('create')->will($this->returnValue(static::TEST_ID));
        $this->requestMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        $this->requestMock->expects($this->once())->method('getHeaderLine')
            ->will($this->returnValue(static::TEST_ID));
        $this->responseMock->expects($this->once())->method('withHeader')->will($this->returnSelf());
        // Act
        call_user_func_array($this->sut, [
            $this->requestMock,
            $this->responseMock,
            $this->getFakeNextCallable()
        ]);
        // Assert
        $this->assertSame(static::TEST_ID, $dummy->foo);
    }
}
