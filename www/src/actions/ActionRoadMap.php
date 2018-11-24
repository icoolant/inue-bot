<?php
namespace app\actions;


class ActionRoadMap extends BaseAction
{
    public function execute($messageBody): void
    {
        $this->sendResponse('Карта проезда...');
    }

}