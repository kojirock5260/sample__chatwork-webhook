<?php

require_once realpath(__DIR__) . "/../vendor/autoload.php";
require_once realpath(__DIR__) . "/../pimple_setting.php";

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$request_body   = file_get_contents('php://input');
$chatwork_token = getenv('CHATWORK_WEBHOOK_MY_MENTION_TOKEN');

$app = $container['app'];
$app->run($request_body, $chatwork_token);