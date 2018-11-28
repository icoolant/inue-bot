<?php
namespace app\actions;


use app\Registration;
use app\Storage;
use VK\Client\VKApiClient;

class ActionRegister extends BaseAction
{
    protected $registration;
    protected $storage;
    protected $object;

    public function __construct(VKApiClient $api, string $userId, Registration $registration, Storage $storage, array $object)
    {
        parent::__construct($api, $userId);
        $this->registration = $registration;
        $this->storage = $storage;
        $this->object = $object;
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
                $reg->age = (int)$messageBody;
                $reg->step = 3;
                $stepMessage = 'Из какого вы города?';
                break;
            case 3:
                $reg->city = $messageBody;
                $reg->step = 4;
                $stepMessage = 'Из какой церкви?';
                break;
            case 4:
                $reg->church = $messageBody;
                $reg->step = 5;
                $stepMessage = 'Требуется вам расселение?';
                break;
            case 5:
                $reg->resettlement = $this->parseBool($messageBody);
                $reg->step = 6;
                $stepMessage = 'Теперь вы можете оплатить регистрацию, прикрепив платеж к сообщению';
                break;
            case 6:
                if ($this->isPayment($this->object)) {
                    $reg->step = 7;
                    $reg->paid = 1;
                    $stepMessage = 'Спасибо, вы зарегистрированы! В течении дня адмисистратор проверит ваш платеж.';
                } else {
                    $stepMessage = 'Пожалуйста, прикрепите платеж к сообщению';
                }
                break;
            case 7:
                $stepMessage = 'Вы уже зарегистрировались';
                break;
            default:
                $reg->step = 1;
                $stepMessage = 'Для регистрации введите ФИО';
        }
        if ($db->setUserData($reg) && $stepMessage) {
            $this->sendResponse($stepMessage);
        }
        if ($errors = $reg->getErrors()) {
            $this->sendResponse('Ошибка, '.implode(' и ', $errors).'.');
        }
        if ($errors = $db->getErrors()) {
            $this->sendResponse('Ошибка, '.implode(' и ', $errors).'.');
        }

//        $this->sendResponse(print_r($reg, true));
    }

    protected function parseBool(string $value): ?bool
    {
        if (preg_match('/да|da|yes/iu', ($value)) === 1) {
            return true;
        }
        if (preg_match('/нет|net|no/iu', ($value)) === 1) {
            return false;
        }
        return null;
    }

    protected function isPayment($object)
    {
        $attachment = $object['attachments'][0] ?? false;
        if ($attachment['type'] == 'link' ) {
            $link = $attachment['link'];
            if ($link['url'] == 'https://m.vk.com/landings/moneysend' &&
                $link['caption'] == 'Денежный перевод')
            {
                $amount = substr($link['title'], 0, 3);
                if ($amount > 100) {
                    return true;
                }
            }

        }
        return false;
    }
}