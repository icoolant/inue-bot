<?php
namespace app\actions;

use app\Keyboard;
use app\ServerHandler;
use VK\Client\VKApiClient;

abstract class BaseAction
{
    const INFO = 'info';
    const REGISTER = 'register';
    const ROAD_MAP = 'read-map';
    const ADMIN_ACCEPT = 'accept';

    protected $api;
    protected $userId;

    public function __construct(VKApiClient $api, string $userId)
    {
        $this->api = $api;
        $this->userId = $userId;
    }

    abstract public function execute($messageBody): void;

    final protected function sendResponse(string $message, Keyboard $keyboard = null): void
    {
        $data = [
            'peer_id' => $this->userId,
            'message' => $message,
        ];
        if ($keyboard) {
            $data['keyboard'] = $keyboard;
        }
        $this->api->messages()->send(API_KEY, $data);
    }

    final public function isContinueRegistration(): bool
    {

    }
}