<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->value();
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'bg-amber-50 text-amber-700 ring-amber-200',
            self::PAID => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            self::PROCESSING => 'bg-sky-50 text-sky-700 ring-sky-200',
            self::SHIPPED => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            self::COMPLETED => 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-200',
            self::CANCELLED => 'bg-rose-50 text-rose-700 ring-rose-200',
            self::EXPIRED => 'bg-slate-100 text-slate-600 ring-slate-200',
        };
    }

    public static function paidStates(): array
    {
        return [
            self::PAID,
            self::PROCESSING,
            self::SHIPPED,
            self::COMPLETED,
        ];
    }
}
