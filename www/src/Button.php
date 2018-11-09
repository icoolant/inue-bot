<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10.11.2018
 * Time: 1:59
 */

namespace app;


class Button
{
    const COLOR_NEGATIVE = 'negative';
    const COLOR_POSITIVE = 'positive';
    const COLOR_DEFAULT = 'default';
    const COLOR_PRIMARY = 'primary';
    const TYPE_TEXT = 'text';

    protected  $type;
    protected  $payload;
    protected  $label;
    protected  $color;

    public function __construct(string $label, $color = self::COLOR_DEFAULT, array $payload = [], $type = self::TYPE_TEXT)
    {
        $this->label = $label;
        $this->payload = $payload;
        $this->type = $type;
        $this->color = $color;
    }

    public function toArray(): array
    {
        return [
            'action' => [
                'type' => $this->type,
                'payload' => json_encode($this->payload, JSON_UNESCAPED_UNICODE),
                'label' => $this->label,
            ],
            'color' => $this->color
        ];
    }

}