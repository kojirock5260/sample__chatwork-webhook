<?php

namespace Kojirock\Domain;

use Kojirock\Domain\Chatwork\ChatworkSecurityChecker;
use Kojirock\Domain\Chatwork\ChatworkNotifier;
use Kojirock\Domain\MessageDriver\FreeTalkMessageDriver;
use Kojirock\Domain\MessageDriver\MessageDriverResolver;
use Psr\Log\LoggerInterface;

class App
{
    /**
     * @var ChatworkSecurityChecker
     */
    private $checker = null;

    /**
     * @var ChatworkNotifier
     */
    private $notifier = null;

    /**
     * @var MessageDriverResolver
     */
    private $driver_resolver = null;

    /**
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * App constructor.
     * @param ChatworkSecurityChecker $checker
     * @param ChatworkNotifier $notifier
     * @param MessageDriverResolver $driver_resolver
     * @param LoggerInterface $logger
     */
    public function __construct(ChatworkSecurityChecker $checker, ChatworkNotifier $notifier, MessageDriverResolver $driver_resolver, LoggerInterface $logger)
    {
        $this->checker         = $checker;
        $this->notifier        = $notifier;
        $this->driver_resolver = $driver_resolver;
        $this->logger          = $logger;
    }

    /**
     * Bot処理実行
     * @param string $request_body
     * @param string $chatwork_token
     */
    public function run($request_body, $chatwork_token)
    {
        // チャットワークトークンチェック
        if ($this->checker->isValid($request_body, $chatwork_token) === false) {
            $this->logger->error("チャットワークトークンチェック失敗");
            die();
        }

        // リクエストBodyから各パラメータを取得
        $request_data = json_decode($request_body, true);
        $room_id      = isset($request_data['webhook_event']['room_id']) ? $request_data['webhook_event']['room_id'] : null;
        $message_body = isset($request_data['webhook_event']['body'])    ? $request_data['webhook_event']['body']    : null;
        if (strlen($room_id) === 0 || strlen($message_body) === 0) {
            // 必須パラメータがない場合はエラー
            $this->logger->error(sprintf("パラメータがありません。 room_id: %s, message_body: %s", $room_id, $message_body));
            die();
        }

        try {
            $this->execute($room_id, $message_body);
        } catch (\LogicException $e) {
            $this->logger->error($e->getMessage());
            die();
        }
    }

    /**
     * 実行処理本体
     * @param int    $room_id
     * @param string $message_body
     * @throws \LogicException
     */
    private function execute($room_id, $message_body)
    {
        // フォーマットデータを取得
        $message_data = $this->messageFormat($message_body);

        // メッセージドライバーを取得
        $driver = $this->driver_resolver->resolve($message_data['type']);
        $driver->setData($message_data['body'], $room_id);

        if ($driver instanceof FreeTalkMessageDriver) {
            // 雑談APIの場合はリゾルバの仕様上bodyが取れないので、メッセージを保存し直す
            $message_data = explode("\n", $message_body);
            unset($message_data[0]);
            $driver->setData(implode("\n", $message_data), $room_id);
        }

        // チャットワーク通知
        $this->notifier->send($room_id, $driver->getSendMessage());
    }

    /**
     * データを整形する
     * @param string $message_data
     * @return array
     */
    private function messageFormat($message_data)
    {
        // メッセージを配列に変換
        $exploded_message_data = explode("\n", $message_data);

        // 配列の1番目(2行目)を取得した後、いらないものを配列から削除して、再度文字列へ戻す
        $message_type = $exploded_message_data[1];
        unset($exploded_message_data[0]);
        unset($exploded_message_data[1]);
        $message_body = implode("\n", $exploded_message_data);

        return [
            'type' => trim($message_type),
            'body' => trim($message_body)
        ];
    }
}