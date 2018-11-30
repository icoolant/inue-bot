<?php
namespace app\actions;

class ActionMessage extends BaseAction
{
    public function execute($messageBody): void
    {
        $this->sendResponse($messageBody);
    }

}