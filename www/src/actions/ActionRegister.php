<?php
namespace app\actions;


use app\Registration;
use app\Storage;
use VK\Client\VKApiClient;

class ActionRegister extends BaseAction
{
    protected $registration;
    protected $storage;

    public function __construct(VKApiClient $api, string $userId, Registration $registration, Storage $storage)
    {
        parent::__construct($api, $userId);
        $this->registration = $registration;
        $this->storage = $storage;
    }

    public function execute($messageBody): void
    {
        $reg = $this->registration;
        $db = $this->storage;
        $stepMessage = null;
        switch ($reg->step) {
            case 1:
                $reg->full_name = $messageBody;
                $reg->step = 2;
                $stepMessage = 'Введите ваш возраст';
                break;
            case 2:
                $reg->age = $messageBody;
                $reg->step = 3;
                $stepMessage = 'Из какого вы города?';
                break;
            case 3:
                $reg->city = $messageBody;
                $reg->step = 4;
                $stepMessage = 'Из какой церкви?';
                break;
            case 4:
                $reg->city = $messageBody;
                $reg->step = 5;
                $stepMessage = 'Требуется вам расселение?';
                break;
            case 5:
                $reg->resettlement = $messageBody;
                $reg->step = 6;
                $stepMessage = 'Теперь вы можете оплатить регистрацию!';
                break;
            default:
                $stepMessage = 'Для регистрации введите ФИО';
        }
        if ($db->setUserData($reg) && $stepMessage) {
            $this->sendResponse($stepMessage);
        }
        if ($errors = $reg->getErrors()) {
            $this->sendResponse('Ошибка, '.implode(' и ', $errors).'.');
        }
    }

}