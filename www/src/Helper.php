<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 30.11.2018
 * Time: 12:22
 */

namespace app;


class Helper
{
    public static function botMessage(string $message, ?array $params = null): string
    {
        $msg = BOT_MESSAGES[$message] ?? $message;
        if ($params) {
            $msg = vsprintf($msg, $params);
        }
        return $msg;
    }
}