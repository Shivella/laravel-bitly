<?php

namespace Tests\Shivella\Bitly\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
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

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    private $guzzle;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Request */
    private $request;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface */
    private $response;

    /** @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface */
    private $stream;

    public function setUp()
    {
        $this->guzzle   = $this->createClientInterfaceMock();
        $this->request  = $this->createRequestMock();
        $this->response = $this->createResponseInterfaceMock();
        $this->stream   = $this->createStreamInterfaceMock();

        $this->bitlyClient = new BitlyClient($this->guzzle, 'test-token');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private function createClientInterfaceMock()
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->getMock();
    }

    public function testGetUrl()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response.json'));

        $this->assertSame('http://bit.ly/1nRtGA', $this->bitlyClient->getUrl('https://www.test.com/foo'));
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\InvalidResponseException
     */
    public function testGetUrlInvalidResponseException()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(403);

        $this->response->expects($this->never())
            ->method('getBody');

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\AccessTokenMissingException
     */
    public function testGetUrlNoCredentials()
    {
        $bitlyClient = new BitlyClient($this->guzzle, null);

        $bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\InvalidResponseException
     */
    public function testGetUrlInvalidResponse()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/invalid.json'));

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\InvalidResponseException
     */
    public function testGetUrlInvalidResponseNotFound()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(400);

        $this->response->expects($this->never())
            ->method('getBody');

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\InvalidResponseException
     */
    public function testGetUrlInvalidResponseInvalidStatusCodeResponse()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response_statuscode.json'));

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\InvalidResponseException
     */
    public function testApiLimitReached()
    {
        $this->guzzle->expects($this->once())
            ->method('send')
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/api_limit_reached.json'));

        $this->bitlyClient->getUrl('https://www.test.com/foo');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function createRequestMock()
    {
        return self::getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private function createResponseInterfaceMock()
    {
        return self::getMockBuilder(ResponseInterface::class)
            ->getMock();
    }

    private function createStreamInterfaceMock()
    {
        return self::getMockBuilder(StreamInterface::class)
            ->getMock();
    }
}
