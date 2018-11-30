<?php
namespace app;

use app\actions\ActionDefault;
use app\actions\ActionInfo;
use app\actions\ActionMessage;
use app\actions\ActionRegister;
use app\actions\ActionRoadMap;
use app\actions\BaseAction;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    protected $api;
    protected $logger;
    protected $storage;
    /** @var Registration */
    protected $registration;
    protected $peerId;

    public function __construct(VKApiClient $api, Logger $logger, Storage $storage)
    {
        $this->api = $api;
        $this->logger = $logger;
        $this->storage = $storage;
    }

    function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === SECRET && $group_id === GROUP_ID) {
            echo CONFIRMATION_TOKEN;
        } else {
            echo 'Error';
        }
    }

    /**
     * @inheritdoc
     */
    public function parse($event): void
    {
        $object = $event->object ?? [];
        $this->peerId = $object->peer_id ?? $object->user_id ?? null;
        if ($this->peerId) {
            $this->registration = $this->storage->getUserData($this->peerId);
        }
        parent::parse($event);
    }

    /**
     * @inheritdoc
     */
    public function messageNew(int $group_id, ?string $secret, array $object)
    {
//        $this->logger->debug(print_r($object, true));
        $actionName = null;
        $messageBody = $object['text'] ?? null;
        if (isset($object['payload'])) {
            $payload = json_decode($object['payload'], true);
            $actionName = $payload['action'] ?? null;
        }
        $action = null;
        switch ($actionName) {
            case BaseAction::INFO:
                $action = new ActionInfo($this->api, $this->peerId);
                break;
            case BaseAction::REGISTER:
                $action = new ActionRegister($this->api, $this->peerId, $this->registration, $this->storage, $object);
                break;
            case BaseAction::ROAD_MAP:
                $action = new ActionRoadMap($this->api, $this->peerId);
                break;
            default:
                if ($this->registration->step < 7) {
                    $action = new ActionRegister($this->api, $this->peerId, $this->registration, $this->storage, $object);
                } else {
                    $action = new ActionDefault($this->api, $this->peerId);
                }
        }
        if ($action) {
            $action->execute($messageBody);
        }
    }

    public function messageReply(int $group_id, ?string $secret, array $object)
    {
        $messageBody = $object['text'] ?? null;
        $reg = $this->registration;
        if ((preg_match('/платеж принят|платёж принят/iu', ($messageBody)) === 1)) {
            if ($reg->isPaidEnough()) {
                $reg->paid = 1;
                $this->storage->setUserData($reg);
            } else {
                $error =  Helper::botMessage('admin-error.failed-accept-payment', [$reg->peer_id, (float)$reg->paid_in_currency, (float)REG_PRICE]);
                $this->logger->debug($error);
                (new ActionMessage($this->api, $object['from_id']))->execute($error);
            }
        }
    }
}