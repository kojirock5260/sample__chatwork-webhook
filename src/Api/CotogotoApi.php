<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/02
 * Time: 21:24
 */

namespace Kojirock\Api;

use \GuzzleHttp\Client;

class CotogotoApi implements ApiIn
{
    const BASE_URL       = 'https://www.cotogoto.ai/webapi/';
    const STUDY_ON       = 1;
    const PERSONA_NORMAL = 0;

    /**
     * @var string
     */
    private $api_key = null;

    /**
     * CotogotoApi constructor.
     * @param string $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * APIコールを実行
     * @param string $message
     * @param array $params
     * @return array
     */
    public function call($message, array $params = [])
    {
        // Guzzleを設定
        $guzzle_client = new Client([
            'base_uri' => self::BASE_URL
        ]);

        // コール実行
        $response_data = $guzzle_client->get('noby.json', [
            'query' => [
                'appkey'  => $this->api_key,
                'text'    => $message,
                'study'   => isset($params['study'])   ? $params['study']   : self::STUDY_ON,
                'persona' => isset($params['persona']) ? $params['persona'] : self::PERSONA_NORMAL,
                'ending'  => isset($params['ending'])  ? $params['ending']  : '',
            ]
        ])->getBody()->getContents();

        return json_decode($response_data, true);
    }
}