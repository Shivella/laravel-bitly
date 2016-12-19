<?php

namespace Shivella\Bitly\Tests\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Shivella\Bitly\Client\BitlyClient;

/**
 * Class BitlyClientTest
 */
class BitlyClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var BitlyClient */
    private $bitlyClient;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    private $guzzleClient;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Request */
    private $request;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface */
    private $response;

    public function setUp()
    {
        $this->guzzleClient = $this->createClientInterfaceMock();
        $this->request = $this->createRequestMock();
        $this->response = $this->createResponseInterfaceMock();

        $this->bitlyClient = new BitlyClient($this->guzzleClient, 'test-token');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private function createClientInterfaceMock()
    {
        return $this->getMock(ClientInterface::class);
    }

    /**
     * @expectedException \Shivella\Bitly\Exceptions\AccessDeniedException
     */
    public function testGetUrl()
    {
        $this->guzzleClient->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(403);

        $this->bitlyClient->getUrl('https:www.test.com/foo');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function createRequestMock()
    {
        return $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private function createResponseInterfaceMock()
    {
        return $this->getMock(ResponseInterface::class);
    }
}
