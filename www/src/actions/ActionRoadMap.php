<?php
namespace app\actions;


use app\Helper;

class ActionRoadMap extends BaseAction
{
    public function execute($messageBody): void
    {
        $this->sendResponse(Helper::botMessage('road-map'));
    }

}