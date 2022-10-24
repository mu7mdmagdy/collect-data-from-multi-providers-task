<?php

namespace PaymentApp\UserModule\DTOs;

use Illuminate\Support\Collection;

class UserDTO
{
    public string $short_uuid;
    public ?string $name;
    public string $email;
    public string $currency;
    public float $balance;
    public array|Collection|null $transactions;


    /**
     * @param string $short_uuid
     */
    public function setShortUuid(string $short_uuid): void
    {
        $this->short_uuid = $short_uuid;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @param array|Collection|null $transactions
     */
    public function setTransactions(array|Collection|null $transactions): void
    {
        $this->transactions = $transactions;
    }


}
