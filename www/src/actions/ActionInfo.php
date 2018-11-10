<?php
namespace app\actions;


class ActionInfo extends BaseAction
{
    public function execute(): void
    {
        $this->sendResponse('Информация о конференции...');
    }

}