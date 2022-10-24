<?php

namespace PaymentApp\TransactionModule\Repositories;

use PaymentApp\Base\Repository;
use PaymentApp\TransactionModule\Models\Transaction;

class TransactionRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Transaction());
    }
}
