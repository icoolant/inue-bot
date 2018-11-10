<?php
namespace app\actions;


class ActionRegister extends BaseAction
{
    public function execute(): void
    {
        $this->sendResponse('Регистрация...');
    }

}