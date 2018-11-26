<?php
require_once '../vendor/autoload.php';
require_once '../config.php';

use VK\Client\VKApiClient;

$api = new VKApiClient();
$logger = new \app\Logger();
$storage = new \app\Storage();
$handler = new \app\ServerHandler($api, $logger, $storage);

$eventData = json_decode(file_get_contents('php://input'));
if ($eventData) {
    $handler->parse($eventData);
    echo 'OK';
} else {
    $logger->debug(print_r($_POST, true));
    if (isset($_POST['sig'])) {
        $paymentHandler = new \app\PaymentHandler($logger);
        $paymentHandler->parse($_POST);
    } else {
        include '../src/view/payment.php';
    }
}