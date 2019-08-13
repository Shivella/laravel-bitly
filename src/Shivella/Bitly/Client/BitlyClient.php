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

use function json_decode;
use function json_encode;

/**
 * Class BitlyClient
 */
class BitlyClient
{
    /** @var ClientInterface */
    private $client;

    /** @var string $token */
    private $token;

    /**
     * @param ClientInterface $client
     * @param string          $token
     */
    public function __construct(ClientInterface $client, $token)
    {
        $this->client = $client;
        $this->token  = $token;
    }

    /**
     * @param string $url
     *
     * @throws InvalidResponseException
     * @throws AccessDeniedException
     *
     * @return string
     */
    public function getUrl(string $url): string
    {
        try {
            $requestUrl = 'https://api-ssl.bitly.com/v4/shorten';

            $header = [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
            ];

            $trequest = new Request('POST', $requestUrl, $header, json_encode(['long_url' => $url]));

            $response = $this->client->send($trequest);

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
