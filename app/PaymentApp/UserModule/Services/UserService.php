<?php

namespace PaymentApp\UserModule\Services;

use PaymentApp\Base\Service;
use PaymentApp\UserModule\Mappers\UserDTOMapper;
use PaymentApp\UserModule\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\Paginator;

class UserService extends Service
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }


    public function getUsersWithTransactions(
        array|string $statuses = [],
        array|string $currencies = [],
        ?float $minAmount = null,
        ?float $maxAmount = null,
        string $dateFrom = '',
        string $dateTo = '',
        int $pageSize = 10,
        int $pageNo = 1,
    ): ?Paginator
    {
        $statuses = !is_array($statuses) ? [$statuses] : $statuses;
        $currencies = !is_array($currencies) ? [$currencies] : $currencies;

        /** clean string - handling sql injection
         * @see cleanString()
        */
        $statuses = array_map('cleanString', $statuses);
        $currencies = array_map('cleanString', $currencies);

        /** upper case statuses & currencies to be like in the database
         * @see TransactionStatus
         */
        $statuses = array_map('strtoupper', $statuses);
        $currencies = array_map('strtoupper', $currencies);

        // get filtered users
        $data = $this->userRepository->getUsersWithTransactions(
            $statuses,
            $currencies,
            $minAmount,
            $maxAmount,
            $dateFrom,
            $dateTo,
            $pageSize,
            $pageNo
        );

        // map users to produce clean API response
        $collection = $data->getCollection();
        $mappedData = $collection->map(function ($user) {
            $userDTOMapper = new UserDTOMapper();
            return $userDTOMapper->setUser($user)->map()->withTransactions()->get();
        });
        $data->setCollection($mappedData);
        return $data;
    }
}
