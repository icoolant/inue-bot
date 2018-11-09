<?php
namespace app;

use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    const SECRET = 'YHdfs432';
    const GROUP_ID = 173737336;
    const CONFIRMATION_TOKEN = 'c715b437';
    const API_KEY = '8036f5b12eddb1dd9f7fe8aecc7663271d07c528a9e38bc69143e03c7a4ab2a3174c05f8e23cc7df6dedd';

    protected $api;
    protected $logger;

    public function __construct(VKApiClient $api, Logger $logger)
    {
        $this->api = $api;
        $this->logger = $logger;
    }

    function confirmation(int $group_id, ?string $secret) {
        if ($secret === static::SECRET && $group_id === static::GROUP_ID) {
            echo static::CONFIRMATION_TOKEN;
        }
    }

    /**
     * @inheritdoc
     */
    public function messageNew(int $group_id, ?string $secret, array $object) {
        $this->logger->debug(print_r(['gr' => $group_id, 'ob' => $object, 'sec' => $secret], true));
        $this->api->messages()->markAsRead(self::API_KEY, [
            'peer_id' => $object['peer_id'],
            'group_id' => $group_id,
            'start_message_id' => $object['conversation_message_id'],
        ]);
        $kb = new Keyboard();
        $kb->addButton(new Button('Информация о конференции', Button::COLOR_PRIMARY, ['action' => 'info']))
            ->addButton(new Button('Регистрация', Button::COLOR_POSITIVE, ['action' => 'register']), 1);
        $this->api->messages()->send(self::API_KEY, [
            'peer_id' => $object['peer_id'],
            'message' => $object['payload'],
            'keyboard' => (string)$kb
        ]);

    }
}