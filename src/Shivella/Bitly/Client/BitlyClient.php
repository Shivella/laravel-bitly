<?php
/*
* (c) Wessel Strengholt <wessel.strengholt@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Shivella\Bitly\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Shivella\Bitly\Exceptions\AccessDeniedException;
use Shivella\Bitly\Exceptions\AccessTokenMissingException;
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
     * @throws AccessTokenMissingException
     * @throws InvalidResponseException
     * @throws AccessDeniedException
     *
     * @return string
     */
    public function getUrl($url)
    {
        if ($this->token === null) {
            throw new AccessTokenMissingException('Access token is not set');
        }

        try {
            $requestUrl = sprintf('https://api-ssl.bitly.com/v3/shorten?longUrl=%s&access_token=%s', $url, $this->token);
            $response = $this->client->send(new Request('GET', $requestUrl));

            if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token');
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['status_txt']) && $data['status_txt'] === 'RATE_LIMIT_EXCEEDED') {
		        throw new InvalidResponseException('You have reached the API rate limit, please try again later');
            }

            if (false === isset($data['data']['url'])) {
                throw new InvalidResponseException('The response does not contain a shortened link');
            }

            if ($data['status_code'] !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            return $data['data']['url'];

        } catch (\Exception $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }
    }
}
