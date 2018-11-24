<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 22.11.2018
 * Time: 15:32
 */

namespace app;


class Registration
{
    /** @var integer */
    public $peer_id;
    /** @var string */
    public $full_name;
    /** @var integer */
    public $age;
    /** @var string */
    public $city;
    /** @var string */
    public $church;
    /** @var boolean */
    public $resettlement;
    /** @var boolean */
    public $paid;
    /** @var integer */
    public $step;

    protected $errors = [];

    public function attributes(): array
    {
        return ['peer_id', 'full_name', 'age', 'city', 'church', 'resettlement', 'paid', 'step'];
    }

    public function validate(): bool
    {
        switch ($this->step) {
            case 6:
                if ($this->resettlement === null || !is_bool($this->resettlement))  {
                    $this->errors[] = 'расселение задано не верно';
                }
            case 5:
                if (!$this->church || strlen($this->church) < 2)  {
                    $this->errors[] = 'церковь задана не верно';
                }
            case 4:
                if (!$this->city || strlen($this->city) < 2)  {
                    $this->errors[] = 'город задан не верно';
                }
            case 3:
                if (!$this->age || !is_numeric($this->age) || $this->age < 1 || $this->age > 90) {
                    $this->errors[] = 'не верный возраст';
                }
            case 2:
                if (!$this->full_name || strlen($this->full_name) < 5)  {
                    $this->errors[] = 'ФИО задано не верно ';
                }
            case 1:
            default:
                if (!$this->peer_id || !is_numeric($this->peer_id)) {
                    $this->errors[] = 'пользователь не найден';
                }
        }
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}