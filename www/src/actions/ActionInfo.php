<?php
namespace app\actions;


class ActionInfo extends BaseAction
{
    public function execute($messageBody): void
    {
        $this->sendResponse('Информация о конференции...');
    }

}