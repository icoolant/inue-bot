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

    public function __construct(VKApiClient $api, Logger $logger, Storage $storage)
    {
        $this->api = $api;
        $this->logger = $logger;
        $this->storage = $storage;
    }

    function confirmation(int $group_id, ?string $secret) {
        if ($secret === SECRET && $group_id === GROUP_ID) {
            echo CONFIRMATION_TOKEN;
        }
    }

    /**
     * @inheritdoc
     */
    public function messageNew(int $group_id, ?string $secret, array $object) {
//        $this->logger->debug(print_r(['gr' => $group_id, 'ob' => $object, 'sec' => $secret], true));
        $userId = $object['peer_id'] ?? null;
        $actionName = null;
        if (isset($object['payload'])) {
            $payload = json_decode($object['payload'], true);
            $actionName = $payload['action'] ?? null;
        }
        switch ($actionName) {
            case BaseAction::INFO:
                $action = new ActionInfo($this->api, $userId);
                break;
            case BaseAction::REGISTER:
                $action = new ActionRegister($this->api, $userId);
                break;
            case BaseAction::ROAD_MAP:
                $action = new ActionRoadMap($this->api, $userId);
                break;
            default:
                $action = new ActionDefault($this->api, $userId);
        }
        $action->execute();
    }
}