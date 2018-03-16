<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 0:34
 */

namespace Kojirock\Domain\MessageDriver;

interface MessageDriverIn
{
    /**
     * データをセットする
     * @param array $message_data
     * @param int   $room_id
     */
    public function setData($message_data, $room_id);

    /**
     * メッセージを取得
     * @return string
     */
    public function getSendMessage();

    /**
     * 対応するタイプかどうか
     * @param string $message_type
     * @return bool
     */
    public function support($message_type);
}