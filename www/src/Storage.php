<?php

namespace app;

class Storage
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO(DB_DSN, DB_USER, DB_PASSWORD, DB_OPTIONS);
    }


    public function getUserData($peerId) :?Registration
    {
        $stmt = $this->pdo->prepare('select * from registration where peer_id = ?');
        $stmt->execute([$peerId]);
        $obj = $stmt->fetchObject(Registration::class);
        if (!$obj) {
            $stmt = $this->pdo->prepare('insert into registration (`peer_id`) values (?)');
            if ($stmt->execute([$peerId])) {
                $obj = new Registration();
                $obj->peer_id = $peerId;
            }
        }
        return $obj;
    }

    public function setUserData(Registration $reg)
    {
        if ($reg->validate()) {
            $stmt = $this->pdo->prepare('update registration set (?)');
            $stmt->execute([
                implode(',', array_map(function ($attribute) use ($reg) {
                    if ($reg->{$attribute} === null) {
                        $val = 'null';
                    } else {
                        $val = '"'.$reg->{$attribute}.'"';
                    }
                    return '`'.$attribute.'` = '.$val;
                }, $reg->attributes())),
            ]);
        }
    }
}