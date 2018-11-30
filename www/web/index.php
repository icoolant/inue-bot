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
    $logger->debug(print_r($eventData, true));
    $handler->parse($eventData);
    if ($eventData->type != 'confirmation') {
        echo 'OK';
    }
} else {
//    $logger->debug(print_r($_POST, true));
//    if (isset($_POST['sig'])) {
//        $paymentHandler = new \app\PaymentHandler($api, $logger, $storage);
//        $paymentHandler->parse($_POST);
//    } else {
//        include '../src/view/payment.php';
//    }
    if (isset($_GET['action']) && $_GET['action'] == 'registered') {
        include '../src/view/registered.php';
    } else {
        echo 'Unknown action';
    }
}