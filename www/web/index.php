<?php
require_once '../vendor/autoload.php';
require_once '../config.php';

use VK\Client\VKApiClient;

$api = new VKApiClient();
$logger = new \app\Logger();
$storage = new \app\Storage();
$handler = new \app\ServerHandler($api, $logger, $storage);

$data = json_decode(file_get_contents('php://input'));
if ($data) {
    $handler->parse($data);
    echo 'OK';
} else {
    echo 'Nothing to do.';
}