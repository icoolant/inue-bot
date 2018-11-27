<?php

namespace app;

class Storage
{
    protected $pdo;

    protected $errors = [];

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

    public function setUserData(Registration $reg): bool
    {
        $pdo = $this->pdo;
        if ($reg->validate()) {
            $params = implode(',', array_map(function ($attribute) use ($reg, $pdo) {
                $attrVal = $reg->{$attribute};
                if (is_null($attrVal)) {
                    $val = 'null';
                } elseif(is_bool($attrVal)) {
                    $val = (int)$attrVal;
                } elseif (is_numeric($attrVal)) {
                    $val = $attrVal;
                } else  {
                    $val = $pdo->quote($attrVal);
                }
                return '`'.$attribute.'` = '.$val;
            }, $reg->attributes()));
            $stmt = $pdo->prepare("update registration set $params where peer_id = ?");
            $res = $stmt->execute([$reg->peer_id]);
            if (!$res) {
                $this->errors[] = print_r($stmt->errorInfo(), true).$stmt->queryString;
            }
            return count($this->errors) === 0;
        }
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}