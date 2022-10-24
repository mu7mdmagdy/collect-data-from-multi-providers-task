<?php

namespace PaymentApp\TransactionModule\Mappers;

use PaymentApp\TransactionModule\Contexts\TransactionStatus;
use PaymentApp\TransactionModule\DTOs\TransactionDTO;
use PaymentApp\TransactionModule\Models\Transaction;

class TransactionDTOMapper
{
    protected TransactionDTO $transactionDTO;
    protected Transaction $transaction;

    /**
     * @param Transaction $transaction
     * @return TransactionDTOMapper
     */
    public function setTransaction(Transaction $transaction): TransactionDTOMapper
    {
        $this->transaction = $transaction;
        $this->transactionDTO = new TransactionDTO();
        return $this;
    }


    public function map(): static
    {
        $this->transactionDTO->setPaidAmount($this->transaction->paid_amount);
        $this->transactionDTO->setCurrency($this->transaction->currency);
        $this->transactionDTO->setParentEmail($this->transaction->parent_email);
        $this->transactionDTO->setStatus(TransactionStatus::getConstByCode($this->transaction->status_code));
        $this->transactionDTO->setStatusCode($this->transaction->status_code);
        $this->transactionDTO->setPaymentDate($this->transaction->payment_date->setTimezone('UTC')->toISOString());
        $this->transactionDTO->setParentIdentification($this->transaction->parent_identification);

        return $this;
    }

    public function get(): TransactionDTO
    {
        return $this->transactionDTO;
    }
}
