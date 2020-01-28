<?php

namespace Shivella\Bitly\Client\Tests;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Shivella\Bitly\Client\BitlyClient;
use PHPUnit\Framework\TestCase;

/**
 * Class BitlyClientTest
 */
class BitlyClientTest extends TestCase
{
    /** @var BitlyClient */
    private $bitlyClient;

    /** @var ClientInterface */
    private $guzzle;

    /** @var |Request */
    private $request;

    /** @var |ResponseInterface */
    private $response;

    /** @var |StreamInterface */
    private $stream;

    public function setUp() : void
    {
        $this->guzzle   = $this->createClientInterfaceMock();
        $this->request  = $this->createRequestMock();
        $this->response = $this->createResponseInterfaceMock();
        $this->stream   = $this->createStreamInterfaceMock();

        $this->bitlyClient = new BitlyClient($this->guzzle, 'test-token');
    }

    public function testGetUrl()
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects(self::once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects(self::once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response.json'));

        $this->assertSame('http://bit.ly/1VmfKqV', $this->bitlyClient->getUrl('https://www.test.com/foo'));
    }

    public function testGetUrlInvalidResponseException() : void
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(403);

        $this->response->expects(self::any())
            ->method('getBody')
            ->willReturn($this->stream);

        self::expectException(\Shivella\Bitly\Exceptions\InvalidResponseException::class);

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    public function testGetUrlInvalidResponse()
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects(self::once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects(self::once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/invalid.json'));

        $this->expectException(\Shivella\Bitly\Exceptions\InvalidResponseException::class);

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    public function testGetUrlInvalidResponseNotFound()
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(400);

        $this->response->expects(self::any())
            ->method('getBody')
            ->willReturn($this->stream);

        self::expectException(\Shivella\Bitly\Exceptions\InvalidResponseException::class);

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    public function testGetUrlInvalidResponseInvalidStatusCodeResponse()
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects(self::once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects(self::once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response_statuscode.json'));

        self::expectException('\Shivella\Bitly\Exceptions\InvalidResponseException');

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    public function testApiLimitReached()
    {
        $this->guzzle->expects(self::once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects(self::once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects(self::once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/api_limit_reached.json'));

        self::expectException(\Shivella\Bitly\Exceptions\InvalidResponseException::class);

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @return MockObject|ClientInterface
     */
    private function createClientInterfaceMock() : MockObject
    {
        return $this->getMockBuilder(ClientInterface::class)->getMock();
    }

    /**
     * @return MockObject|Request
     */
    private function createRequestMock() : MockObject
    {
        return self::getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return MockObject|ResponseInterface
     */
    private function createResponseInterfaceMock() : MockObject
    {
        return self::getMockBuilder(ResponseInterface::class)->getMock();
    }

    /**
     * @return MockObject|StreamInterface
     */
    private function createStreamInterfaceMock() : MockObject
    {
        return self::getMockBuilder(StreamInterface::class)->getMock();
    }
}
