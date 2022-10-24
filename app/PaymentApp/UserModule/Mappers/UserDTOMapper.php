<?php

namespace PaymentApp\UserModule\Mappers;

use PaymentApp\TransactionModule\Mappers\TransactionDTOMapper;
use PaymentApp\UserModule\DTOs\UserDTO;
use PaymentApp\UserModule\Models\User;

class UserDTOMapper
{
    protected UserDTO $userDTO;
    protected User $user;
    protected TransactionDTOMapper $transactionDTOMapper;

    public function __construct()
    {
        $this->user = new User();
        $this->userDTO = new UserDTO();
        $this->transactionDTOMapper = new TransactionDTOMapper();
    }

    /**
     * @param User $user
     * @return UserDTOMapper
     */
    public function setUser(User $user): UserDTOMapper
    {
        $this->user = $user;
        return $this;
    }


    public function map(): static
    {
        $this->userDTO->setShortUuid($this->user->short_uuid);
        $this->userDTO->setName($this->user->name);
        $this->userDTO->setEmail($this->user->email);
        $this->userDTO->setCurrency($this->user->currency);
        $this->userDTO->setBalance($this->user->balance);

        return $this;
    }

    public function withTransactions(): static
    {
        /** map transactions
         * @see TransactionDTOMapper
         */
        $this->userDTO->setTransactions(
            $this->user->transactions?->map(
                fn($transaction) => $this->transactionDTOMapper->setTransaction($transaction)->map()->get()
            ) ?: []
        );

        return $this;
    }

    public function get(): UserDTO
    {
        return $this->userDTO;
    }
}
