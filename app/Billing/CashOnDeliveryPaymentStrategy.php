<?php

namespace App\Billing;

use Illuminate\Support\Str;
use App\Billing\PaymentStrategyInterface;

class CashOnDeliveryPaymentStrategy implements PaymentStrategyInterface
{

    private $discount;
    private $shippingFees;

    public function __construct()
    {
        $this->discount = 0;
        $this->shippingFees = 5;
    }


    public function setDiscount($discountCode)
    {
        if ($discountCode == 'A3000') {
            return $this->discount = 10;
        }
            return $this->discount = 0;
    }
    public function charge($amount)
    {
        $priceAfterDiscount = round((1 - ($this->discount / 100)) * $amount, 2);
        $PriceWithShippingFees = round((1 + ($this->shippingFees / 100)) * $priceAfterDiscount, 2);

        return [
            'amount' => $amount,
            'dicount_amount' => $this->discount,
            'price_after discount' => $priceAfterDiscount,
            'shipment_fees' => $this->shippingFees,
            'final_price' => $PriceWithShippingFees,
            'confirmation_code' => Str::random(20),
        ];
    }
}
