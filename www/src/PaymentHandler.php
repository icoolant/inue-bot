<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26.11.2018
 * Time: 0:10
 */

namespace app;


use app\actions\ActionRegister;
use VK\Client\VKApiClient;

class PaymentHandler
{
    protected $api;
    protected $logger;
    protected $storage;
    protected $response = [];

    public function __construct(VKApiClient $api, Logger $logger, Storage $storage)
    {
        $this->api = $api;
        $this->logger = $logger;
        $this->storage = $storage;
    }

    public function parse($input)
    {
        $this->logger->debug('test');
        if ($this->signCheck($input)) {
            $this->process($input);
        }
        $this->sendResponse();
    }

    protected function signCheck($input)
    {
        $sig = $input['sig'];
        unset($input['sig']);
        ksort($input);
        $str = '';
        foreach ($input as $k => $v) {
            $str .= $k.'='.$v;
        }
        if ($sig != md5($str.VK_PAYMENT_SECRET)) {
            $this->response['error'] = array(
                'error_code' => 10,
                'error_msg' => 'Несовпадение вычисленной и переданной подписи запроса.',
                'critical' => true
            );
            return false;
        }
        return true;
    }

    protected function process($input)
    {
        switch ($input['notification_type']) {
            case 'get_item':
                // Получение информации о товаре
                $item = $input['item']; // наименование товара
                if ($item == 'inue2019') {
                    $this->response['response'] = array(
                        'item_id' => 1,
                        'title' => 'Регистрация на ИNЫЕ 2019',
                        'photo_url' => 'https://info-gosuslugi.ru/wp-content/uploads/2017/09/registratsiya-fl-gosuslugi-768x493.jpg',
                        'price' => 150
                    );
                }  else {
                    $this->response['error'] = array(
                        'error_code' => 20,
                        'error_msg' => 'Товара не существует.',
                        'critical' => true
                    );
                }
                break;
            case 'get_item_test':
                // Получение информации о товаре в тестовом режиме
                $item = $input['item'];
                if ($item == 'inue2019') {
                    $this->response['response'] = array(
                        'item_id' => 1,
                        'title' => 'Регистрация на ИNЫЕ 2019 (test)',
                        'photo_url' => 'https://info-gosuslugi.ru/wp-content/uploads/2017/09/registratsiya-fl-gosuslugi-768x493.jpg',
                        'price' => 15,
                    );
                } else {
                    $this->response['error'] = array(
                        'error_code' => 20,
                        'error_msg' => 'Товара не существует.',
                        'critical' => true
                    );
                }
                break;
            case 'order_status_change':
                // Изменение статуса заказа
                if ($input['status'] == 'chargeable') {
                    $order_id = intval($input['order_id']);
                    // Код проверки товара, включая его стоимость
                    $app_order_id = 1; // Получающийся у вас идентификатор заказа.

                    $this->response['response'] = array(
                        'order_id' => $order_id,
                        'app_order_id' => $app_order_id,
                    );
                } else {
                    $this->response['error'] = array(
                        'error_code' => 100,
                        'error_msg' => 'Передано непонятно что вместо chargeable.',
                        'critical' => true
                    );
                }
                break;
            case 'order_status_change_test':
                // Изменение статуса заказа в тестовом режиме
                if ($input['status'] == 'chargeable') {
                    $order_id = intval($input['order_id']);
                    $app_order_id = 1; // Тут фактического заказа может не быть - тестовый режим.
                    $peerId = $input['user_id'];
                    $this->response['response'] = array(
                        'order_id' => $order_id,
                        'app_order_id' => $app_order_id,
                    );
                    $action = new ActionRegister($this->api, $peerId, $this->storage->getUserData($peerId), $this->storage);
                    $action->execute(null);
                } else {
                    $this->response['error'] = array(
                        'error_code' => 100,
                        'error_msg' => 'Передано непонятно что вместо chargeable.',
                        'critical' => true
                    );
                }
                break;
        }
    }

    protected function sendResponse()
    {
        $this->logger->debug(print_r($this->response,true));
        if ($this->response) {
            header("Content-Type: application/json; encoding=utf-8");
            echo json_encode($this->response);
            exit;
        }
    }
}