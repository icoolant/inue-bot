<?php
namespace app\actions;


use app\Helper;
use app\Logger;
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
        if ($messageBody == 'Регистрация' && $reg->step > 0) {
            $this->sendResponse(Helper::botMessage('registration.already-started'));
            return;
        }
        $stepMessage = null;
        switch ($reg->step) {
            case 1:
                $reg->full_name = $messageBody;
                $reg->step = 2;
                $stepMessage = Helper::botMessage('registration.age');
                break;
            case 2:
                $reg->age = (int)$messageBody;
                $reg->step = 3;
                $stepMessage = Helper::botMessage('registration.city');
                break;
            case 3:
                $reg->city = $messageBody;
                $reg->step = 4;
                $stepMessage = Helper::botMessage('registration.church');
                break;
            case 4:
                $reg->church = $messageBody;
                $reg->step = 5;
                $stepMessage = Helper::botMessage('registration.resettlement');
                break;
            case 5:
                $reg->resettlement = $this->parseBool($messageBody);
                $reg->step = 6;
                $stepMessage = Helper::botMessage('registration.pay-instructions');
                break;
            case 6:
                if ($payment = $this->getPayment($this->object)) {
                    $reg->paid_in_currency += $payment;
                    $reg->step = 7;
                    if ($reg->isPaidEnough())  {
                        $stepMessage = Helper::botMessage('registration.success');
                    }
                } else {
                    $stepMessage = Helper::botMessage('registration.wrong-message');
                }
                break;
            case 7:
                $stepMessage = Helper::botMessage('registration.already-registered');
                break;
            default:
                $reg->step = 1;
                $stepMessage = Helper::botMessage('registration.full-name');
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

    protected function getPayment($object): ?float
    {
        $attachment = $object['attachments'][0] ?? false;
        //(new Logger())->debug(print_r($attachment, true));
        if ($attachment && $attachment->type == 'link' ) {
            $link = $attachment->link;
            if ($link->url == 'https://m.vk.com/landings/moneysend' &&
                $link->caption == 'Денежный перевод')
            {
                return (float)explode(' ', $link->title)[0];
            }

        }
        return null;
    }
}