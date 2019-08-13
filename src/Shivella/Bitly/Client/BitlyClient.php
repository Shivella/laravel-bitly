<?php
/*
* (c) Wessel Strengholt <wessel.strengholt@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Shivella\Bitly\Client;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Shivella\Bitly\Exceptions\AccessDeniedException;
use Shivella\Bitly\Exceptions\InvalidResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BitlyClient
 */
class BitlyClient
{
    /** @var ClientInterface */
    private $client;

    /** @var string $token */
    private $token;

    /** @var array $header */
    private $header;

    /**
     * @param ClientInterface $client
     * @param string          $token
     */
    public function __construct(ClientInterface $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
        $this->header = [
            'Authorization' => 'Bearer '.$token,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @param string $url
     *
     * @throws InvalidResponseException
     * @throws AccessDeniedException
     *
     * @return string
     */
    public function getUrl(string $url) : string
    {
        try {
            $requestUrl = 'https://api-ssl.bitly.com/v4/shorten';
            $response = $this->client->send(
                new Request('POST', $requestUrl, [
                    'json' => ['long_url' => $url]
                ]),
                ['headers' => $this->header]
            );

            if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token');
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (false === isset($data['link'])) {
                throw new InvalidResponseException('The response does not contain a shortened link');
            }

            return $data['link'];

        } catch (Exception $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }
    }
}
