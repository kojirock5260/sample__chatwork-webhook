<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/16
 * Time: 21:11
 */

namespace Kojirock\Domain\MessageDriver;

use Psr\Log\LoggerInterface;

class MessageDriverResolver
{
    /**
     * @var array
     */
    private $driver_list = [];

    /**
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * MessageFormatResolver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * ドライバーを登録する
     * @param MessageDriverIn $formatter
     */
    public function addDriver(MessageDriverIn $driver)
    {
        $this->driver_list[] = $driver;
    }

    /**
     * 対応するドライバを返す
     * @param string $message_type
     * @return MessageDriverIn
     * @throws \LogicException
     */
    public function resolve($message_type)
    {
        foreach ($this->driver_list as $driver) {
            if ($driver->support($message_type)) {
                return $driver;
            }
        }

        throw new \LogicException("ドライバが取得できない: message_type: " . $message_type);
    }
}