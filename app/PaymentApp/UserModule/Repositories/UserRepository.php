<?php

namespace PaymentApp\UserModule\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use PaymentApp\Base\Repository;
use PaymentApp\UserModule\Models\User;

class UserRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new User());
    }

    public function getUsersWithTransactions(
        array $statuses = [],
        array $currencies = [],
        ?float $minAmount = null,
        ?float $maxAmount = null,
        string $dateFrom = '',
        string $dateTo = '',
        int $pageSize = 10,
        int $pageNo = 1,
    ): ?Paginator
    {
        return $this->getModel()
            ->whereHas('transactions', function ($query) use ($statuses, $currencies, $minAmount, $maxAmount, $dateFrom, $dateTo) {
                if (!empty($statuses)) {
                    $query->whereIn('status', $statuses);
                }
                if (!empty($currencies)) {
                    $query->whereIn('currency', $currencies);
                }
                if ($minAmount) {
                    $query->where('paid_amount', '>=', $minAmount);
                }
                if ($maxAmount) {
                    $query->where('paid_amount', '<=', $maxAmount);
                }
                if (!empty($dateFrom)) {
                    $query->whereDate('payment_date', '>=', $dateFrom);
                }
                if (!empty($dateTo)) {
                    $query->whereDate('payment_date', '<=', $dateTo);
                }
            })
            ->with('transactions')->simplePaginate($pageSize, ['*'], 'page', $pageNo);
    }
}
