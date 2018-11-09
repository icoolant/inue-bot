<?php
require_once './vendor/autoload.php';

use VK\Client\VKApiClient;

$api = new VKApiClient();
$logger = new \app\Logger();
$handler = new \app\ServerHandler($api, $logger);

$data = json_decode(file_get_contents('php://input'));
$handler->parse($data);
echo 'OK';