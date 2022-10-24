<?php

namespace PaymentApp\TransactionModule\DTOs;

class TransactionDTO
{
    public float $paid_amount;
    public string $currency;
    public string $parent_email;
    public string $status;
    public int $status_code;
    public string $payment_date;
    public string $parent_identification;

    /**
     * @param float $paid_amount
     */
    public function setPaidAmount(float $paid_amount): void
    {
        $this->paid_amount = $paid_amount;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @param string $parent_email
     */
    public function setParentEmail(string $parent_email): void
    {
        $this->parent_email = $parent_email;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    /**
     * @param string $payment_date
     */
    public function setPaymentDate(string $payment_date): void
    {
        $this->payment_date = $payment_date;
    }

    /**
     * @param string $parent_identification
     */
    public function setParentIdentification(string $parent_identification): void
    {
        $this->parent_identification = $parent_identification;
    }


}
