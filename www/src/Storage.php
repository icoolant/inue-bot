<?php

namespace app;

class Storage
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO(DB_DSN, DB_USER, DB_PASSWORD, DB_OPTIONS);
    }

    public function getUserData()
    {
        $this->pdo->query('select * from registration where user_id = 1');
    }

    public function setUserData()
    {

    }

    protected function loadData()
    {

    }

}