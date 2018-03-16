<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 15:37
 */

namespace Kojirock\Domain\MessageDriver;

use Kojirock\Api\ApiIn;
use Psr\Log\LoggerInterface;

class PrefCodeMessageDriver implements MessageDriverIn
{
    use MessageDriverTrait;

    const MESSAGE_TYPE_REGEX = '住所教えて|住所おしえて|住所';

    /**
     * APIクラスを格納する
     * @var ApiIn
     */
    private $api = null;

    /**
     * MessageDriverPrefCode constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(ApiIn $api, LoggerInterface $logger)
    {
        $this->api    = $api;
        $this->logger = $logger;
    }

    /**
     * メッセージを取得
     * @return string
     */
    public function getSendMessage()
    {
        // APIコール
        $response = $this->api->call($this->message);
        $response = json_decode($response, true);

        return sprintf("郵便番号: 「%s」の住所は「 %s%s%s 」だよ!",
            $this->message,
            $response['results'][0]['address1'],
            $response['results'][0]['address2'],
            $response['results'][0]['address3']
        );
    }
}