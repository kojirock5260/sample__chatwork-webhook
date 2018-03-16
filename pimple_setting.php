<?php
use Pimple\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Polidog\Chatwork\Client AS PolidogChatworkClient;
use Polidog\Chatwork\Entity\Message AS PolidogChatworkMessage;
use Kojirock\Domain\App;
use Kojirock\Domain\Chatwork\ChatworkNotifier;
use Kojirock\Domain\Chatwork\ChatworkSecurityChecker;
use Kojirock\Domain\MessageDriver\MessageDriverResolver;
use Kojirock\Domain\MessageDriver\CheerMessageDriver;
use Kojirock\Domain\MessageDriver\FreeTalkMessageDriver;
use Kojirock\Domain\MessageDriver\RandomMessageDriver;
use Kojirock\Domain\MessageDriver\PrefCodeMessageDriver;
use Kojirock\Api\ZipCloudApi;
use Kojirock\Api\CotogotoApi;

$container = new Container();

/**
 * Monolog
 * @return Logger
 */
$container['logger'] = function() {
    $path = realpath(__DIR__) . '/logs/default.log';

    $log = new Logger('chatwork_webhook');
    $log->pushHandler(new StreamHandler($path, Logger::DEBUG));

    return $log;
};

/**
 * チャットワーククライアント
 * @return PolidogChatworkClient
 */
$container['chatwork_client'] = function() {
    $chatwork_api_key = getenv("CHATWORK_API_KEY");

    return new PolidogChatworkClient($chatwork_api_key);
};

/**
 * チャットワークメッセージ
 * @return PolidogChatworkMessage
 */
$container['chatwork_message'] = function() {
    return new PolidogChatworkMessage();
};

/**
 * チャットワーク通知クラス
 * @param Container $c
 * @return ChatworkNotifier
 */
$container['chatwork_notifier'] = function($c) {
    $client  = $c['chatwork_client'];
    $message = $c['chatwork_message'];

    return new ChatworkNotifier($client, $message);
};

/**
 * チャットワークのセキュリティチェッカー
 * @return ChatworkSecurityChecker
 */
$container['chatwork_security_checker'] = function() {
    $chatwork_webhook_signature = isset($_REQUEST['chatwork_webhook_signature']) ? $_REQUEST['chatwork_webhook_signature'] : null;

    return new ChatworkSecurityChecker($chatwork_webhook_signature);
};

/**
 * メッセージドライバリゾルバ
 * @param Container $c
 * @return MessageDriverResolver
 */
$container['message_driver_resolver'] = function($c) {
    $resolver = new MessageDriverResolver($c['logger']);
    $resolver->addDriver(new CheerMessageDriver($c['logger']));
    $resolver->addDriver(new PrefCodeMessageDriver(new ZipCloudApi(), $c['logger']));
    $resolver->addDriver(new RandomMessageDriver($c['logger']));

    // 雑談APIはラストにセット
    $resolver->addDriver(new FreeTalkMessageDriver(new CotogotoApi(getenv('COTOGOTO_API_KEY')), $c['logger']));

    return $resolver;
};

/**
 * Appクラス
 * @param Container $c
 * @return App
 */
$container['app'] = function($c) {
    $security_checker = $c['chatwork_security_checker'];
    $notifier         = $c['chatwork_notifier'];
    $driver_resolver  = $c['message_driver_resolver'];
    $logger           = $c['logger'];

    return new App($security_checker, $notifier, $driver_resolver, $logger);
};
