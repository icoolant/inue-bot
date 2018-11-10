<?php
namespace app\actions;

use app\Button;
use app\Keyboard;

class ActionDefault extends BaseAction
{
    public function execute(): void
    {
        $kb = (new Keyboard())
            ->addButton(new Button('Информация о конференции', Button::COLOR_PRIMARY, ['action' => self::INFO]))
            ->addButton(new Button('Регистрация', Button::COLOR_POSITIVE, ['action' => self::REGISTER]))
            ->addButton(new Button('Карта проезда', Button::COLOR_DEFAULT, ['action' => self::ROAD_MAP]));
        $this->sendResponse('Привет!', $kb);
    }

}