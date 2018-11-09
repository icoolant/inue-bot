<?php
namespace app;

class Logger
{
    public function debug(string $str)
    {
        file_put_contents("php://stdout", "$str\n");
    }
}