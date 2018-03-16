<?php

namespace Kojirock\Domain\Chatwork;

use Polidog\Chatwork\ClientInterface;
use Polidog\Chatwork\Entity\EntityInterface;

class ChatworkNotifier
{
    /**
     * @var ClientInterface
     */
    private $client = null;

    /**
     * @var EntityInterface
     */
    private $message = null;

    /**
     * ChatworkNotifier constructor.
     *
     * @param ClientInterface $client
     * @param EntityInterface $message
     */
    public function __construct(ClientInterface $client, EntityInterface $message)
    {
        $this->client  = $client;
        $this->message = $message;
    }

    /**
     * チャットワーク通知実行
     *
     * @param int    $room_id
     * @param string $body
     */
    public function send($room_id, $body)
    {
        $this->message->account = $this->client->api('me')->show();
        $this->message->body    = $body;
        $this->client->api('rooms')->messages($room_id)->create($this->message);
    }
}