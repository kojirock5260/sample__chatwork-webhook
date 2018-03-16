<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/01/30
 * Time: 16:52
 */

namespace Kojirock\Domain\Chatwork;

class ChatworkSecurityChecker
{
    const HASH_ALGORITHM = "sha256";

    /**
     * チャットワークSignature
     * @var string
     */
    private $chatwork_webhook_signature = null;

    /**
     * ChatworkSecurityChecker constructor.
     * @param string $chatwork_webhook_signature
     */
    public function __construct($chatwork_webhook_signature)
    {
        $this->chatwork_webhook_signature = $chatwork_webhook_signature;
    }

    /**
     * Chatworkセキュリティチェック
     *
     * @param string $request_body
     * @param string $chatwork_token
     * @return bool
     */
    public function isValid($request_body, $chatwork_token)
    {
        // Signatureの存在確認
        if (strlen($this->chatwork_webhook_signature) === 0) {
            return false;
        }

        $digest             = hash_hmac(self::HASH_ALGORITHM, $request_body, base64_decode($chatwork_token), true);
        $expected_signature = base64_encode($digest);

        return $this->chatwork_webhook_signature === $expected_signature;
    }
}