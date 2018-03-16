<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 1:34
 */

namespace Kojirock\Domain\MessageDriver;

class RandomMessageDriver implements MessageDriverIn
{
    use MessageDriverTrait;

    const MESSAGE_TYPE_REGEX = '選んで|えらんで';

    /**
     * メッセージを取得
     * @return string
     */
    public function getSendMessage()
    {
        // メッセージを配列へ変換
        $message_data = explode("\n", $this->message);

        // ランダムでデータを取得
        $key     = array_rand($message_data);
        $message = $message_data[$key];

        return "『{$message}』がいいね！";
    }
}