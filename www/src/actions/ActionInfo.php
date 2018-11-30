<?php
namespace app\actions;


use app\Helper;

class ActionInfo extends BaseAction
{
    public function execute($messageBody): void
    {
        $this->sendResponse(Helper::botMessage('conf-info'));
    }

}