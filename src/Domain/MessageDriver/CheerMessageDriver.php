<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 17:00
 */

namespace Kojirock\Domain\MessageDriver;

class CheerMessageDriver implements MessageDriverIn
{
    use MessageDriverTrait;

    const MESSAGE_TYPE_REGEX = 'つらたん|つらいお|つらいぉ';

    // 応援文言リスト
    const CHEER_LIST = [
        '君は頑張ってるよ！',
        '大事なのは勇気だ！',
        '自分の努力を信じて！',
        '君なら出来るよ！',
        '諦めないで！',
        '頑張れ！',
        'みんな君の味方だよ！'
    ];

    /**
     * メッセージを取得
     * @return string
     */
    public function getSendMessage()
    {
        // ランダムでデータを取得
        $key     = array_rand(self::CHEER_LIST);
        $message = self::CHEER_LIST[$key];

        return $message;
    }
}