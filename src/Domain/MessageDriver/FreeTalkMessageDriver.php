<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/02
 * Time: 22:50
 */

namespace Kojirock\Domain\MessageDriver;

use Kojirock\Api\ApiIn;
use Psr\Log\LoggerInterface;

class FreeTalkMessageDriver implements MessageDriverIn
{
    use MessageDriverTrait;

    /**
     * @var ApiIn
     */
    private $api = null;

    /**
     * FreeTalkMessageDriver constructor.
     * @param ApiIn $api
     * @param LoggerInterface $logger
     */
    public function __construct(ApiIn $api, LoggerInterface $logger)
    {
        $this->api    = $api;
        $this->logger = $logger;
    }

    /**
     * 対応するタイプかどうか
     * @param string $message_type
     * @return bool
     */
    public function support($message_type)
    {
        // 雑談は基本的にtrue
        return true;
    }

    /**
     * メッセージを取得
     *
     * @return string
     */
    public function getSendMessage()
    {
        $response = $this->api->call($this->message);

        return $response['text'];
    }
}