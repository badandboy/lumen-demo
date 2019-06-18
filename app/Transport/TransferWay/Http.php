<?php
namespace App\Transport\TransferWay;
use App\Exceptions\CallServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * 统一http调用
 *
 * @author: echo
 * @date:2019.05.05
 */
class Http
{
    public static function call($url, $params, $method = 'POST', $async = false, $headers = [], $timeout = 10)
    {
        try {
            $client = new Client(['timeout' => $timeout]);

            $data = array();
            $contentType = 'application/json';
            if (!empty($headers)) {
                $data['headers'] = $headers;
                if (isset($headers['Content-Type']))
                    $contentType = $headers['Content-Type'];
            }
            if (strtoupper($method) == 'POST') {
                switch ($contentType) {
                    default:
                        $data['body'] = json_encode($params);
                        break;
                }
            } else {
                $url = get_url($url, $params);
            }

            $response = $client->request($method, $url, $data);
            if ($response->getStatusCode() !== 200) {

                $errMessage = '状态为:' . $response->getStatusCode();
                throw new RequestException($errMessage);
            }
            $body = $response->getBody()->getContents();
            $result = $body;
            return $result;

        } catch (RequestException $e) {
            $errMessage = $e->getMessage();
            $errMessage = '#RequestException#' . PHP_EOL . json_encode(compact('url', 'params', 'method', 'headers', 'errMessage', 'response', 'result'));
            throw new CallServiceException($errMessage,$e->getCode());
        }
    }
}