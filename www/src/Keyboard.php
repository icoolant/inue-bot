<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10.11.2018
 * Time: 1:58
 */

namespace app;


class Keyboard
{
    protected $buttons;
    protected $onTime;

    public function __construct(array $buttons = [], bool $onTime = false)
    {
        $this->buttons = $buttons;
        $this->onTime = $onTime;
    }

    public function addButton(Button $button, int $index = 0): self
    {
        $this->buttons[$index][] = $button;
        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'one_time' => $this->onTime,
            'buttons' => array_map(function (array $value) {
                return array_map(function (Button $button) {
                        return $button->toArray();
                }, $value);
            }, $this->buttons),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function __toString()
    {
        return $this->serialize();
    }
}