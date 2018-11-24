<?php
namespace app;

use app\actions\ActionDefault;
use app\actions\ActionInfo;
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
        }
    }

    /**
     * @inheritdoc
     */
    public function parse($event): void
    {
        $object = (array)$event->object;
        $this->peerId = $object['peer_id'] ?? null;
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
//        $this->logger->debug(print_r(['gr' => $group_id, 'ob' => $object, 'sec' => $secret], true));
        $actionName = null;
        $messageBody = $object['body'] ?? '';
        if (isset($object['payload'])) {
            $payload = json_decode($object['payload'], true);
            $actionName = $payload['action'] ?? null;
        }
        switch ($actionName) {
            case BaseAction::INFO:
                $action = new ActionInfo($this->api, $this->peerId);
                break;
            case BaseAction::REGISTER:
                $action = new ActionRegister($this->api, $this->peerId, $this->registration);
                break;
            case BaseAction::ROAD_MAP:
                $action = new ActionRoadMap($this->api, $this->peerId);
                break;
            default:
                if ($this->registration->step) {
                    $action = new ActionRegister($this->api, $this->peerId, $this->registration, $this->storage);
                } else {
                    $action = new ActionDefault($this->api, $this->peerId);
                }
        }
        $action->execute($messageBody);
    }
}