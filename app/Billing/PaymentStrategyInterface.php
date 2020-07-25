<?php

namespace App\Billing;

interface PaymentStrategyInterface
{
    public function setDiscount($amount);
    public function charge($amount);
}
