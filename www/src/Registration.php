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
    /** @var float */
    public $paid_in_currency;

    protected $errors = [];

    public function attributes(): array
    {
        return ['peer_id', 'full_name', 'age', 'city', 'church', 'resettlement', 'paid', 'step', 'paid_in_currency'];
    }

    public function validate(): bool
    {
        switch ($this->step) {
            case 7:
                if (!$this->isPaidEnough())  {
                    $this->errors[] = Helper::botMessage('error.paid-not-enough', [bcsub(REG_PRICE, $this->paid_in_currency, 2)]);
                }
            case 6:
                if ($this->resettlement === null || (!is_bool($this->resettlement) && $this->resettlement > 1))  {
                    $this->errors[] = Helper::botMessage('error.resettlement');
                }
            case 5:
                if (!$this->church || strlen($this->church) < 2)  {
                    $this->errors[] = Helper::botMessage('error.church');
                }
            case 4:
                if (!$this->city || strlen($this->city) < 2)  {
                    $this->errors[] = Helper::botMessage('error.city');
                }
            case 3:
                if (!$this->age || !is_numeric($this->age) || $this->age < 1 || $this->age > 90) {
                    $this->errors[] = Helper::botMessage('error.age');
                }
            case 2:
                if (!$this->full_name || strlen($this->full_name) < 5)  {
                    $this->errors[] = Helper::botMessage('error.full-name');
                }
            case 1:
            default:
                if (!$this->peer_id || !is_numeric($this->peer_id)) {
                    $this->errors[] = Helper::botMessage('error.user-not-found');
                }
        }
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isPaidEnough()
    {
        return bccomp($this->paid_in_currency, REG_PRICE, 2) >= 0;
    }

}