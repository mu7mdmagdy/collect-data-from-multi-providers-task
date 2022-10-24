<?php

namespace PaymentApp\TransactionModule\Contexts;

use ReflectionClass;

class TransactionStatus
{
    const PENDING = 'PENDING';
    const AUTHORIZED = 'AUTHORIZED';
    const DECLINE = 'DECLINE';
    const REFUNDED = 'REFUNDED';

    public static function list(): array
    {
        $reflection = new ReflectionClass(self::class);
        return $reflection->getConstants();
    }

    public static function listReadable(): array
    {
        return [
            self::PENDING => __('status.Pending'),
            self::AUTHORIZED => __('status.Authorized'),
            self::DECLINE => __('status.Decline'),
            self::REFUNDED => __('status.Refunded'),
        ];
    }

    public static function listCode(): array
    {
        return [
            self::PENDING => 0,
            self::AUTHORIZED => 1,
            self::DECLINE => 2,
            self::REFUNDED => 3,
        ];
    }

    public static function getConstByCode(int $code): string
    {
        $list = self::listCode();
        return array_search($code, $list);
    }

    public static function get(string $status): string
    {
        return self::listReadable()[$status];
    }


}
