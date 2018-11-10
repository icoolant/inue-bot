<?php
namespace app\actions;


class ActionRoadMap extends BaseAction
{
    public function execute(): void
    {
        $this->sendResponse('Карта проезда...');
    }

}