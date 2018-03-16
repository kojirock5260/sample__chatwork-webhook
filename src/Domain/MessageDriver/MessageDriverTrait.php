<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 1:41
 */

namespace Kojirock\Domain\MessageDriver;

use Psr\Log\LoggerInterface;

trait MessageDriverTrait
{
    /**
     * @var string
     */
    private $message = null;

    /**
     * @var int
     */
    private $room_id = null;

    /**
     * @var string
     */
    private $match_type = '';

    /**
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * MessageDriverTrait constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * データをセットする
     * @param string $message
     * @param int    $room_id
     */
    public function setData($message, $room_id)
    {
        $this->message = $message;
        $this->room_id = (int)$room_id;
    }

    /**
     * 対応するタイプかどうか
     * @param string $message_type
     * @return bool
     */
    public function support($message_type)
    {
        $pattern = self::MESSAGE_TYPE_REGEX;
        $results = (bool)preg_match("/^({$pattern})$/", $message_type, $matches);
        if ($results === true) {
            $this->match_type = $matches[0];
        }

        return $results;
    }
}