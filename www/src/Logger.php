<?php
namespace app;

class Logger
{
    public function debug(string $str)
    {
        file_put_contents("../app.log", "$str\n", FILE_APPEND);
    }
}