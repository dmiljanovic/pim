<?php

namespace App\Repository;

use App\Database\Db;

class HomeRepository extends Db
{
    /**
     * Method for saving order data in db.
     *
     * @param array $data
     * @return boolean
     * @internal param string $entryId
     */
    public function saveOrder($data)
    {
        $param = [
            'foreign_currency_purchased'        => $data['foreign_currency_purchased'],
            'foreign_currency_exchange_rate'    => round($data['foreign_currency_exchange_rate'], 4),
            'surcharge_percentage'              => round($data['surcharge_percentage'], 2),
            'surcharge_amount'                  => round($data['surcharge_amount'], 4),
            'foreign_currency_purchased_amount' => round($data['foreign_currency_purchased_amount'], 4),
            'amount_paid_usd'                   => round($data['amount_paid_usd'], 4),
            'discount_percentage'               => $data['discount_percentage'] ? round($data['discount_percentage'], 2) : null,
            'discount_amount'                   => $data['discount_amount'] ? round($data['discount_amount'], 4) : null,
            'created_at'                        => date("Y-m-d H:i:s")
        ];

        return self::saveData('
            INSERT INTO orders (
                foreign_currency_purchased, 
                foreign_currency_exchange_rate, 
                surcharge_percentage,
                surcharge_amount,
                foreign_currency_purchased_amount,
                amount_paid_usd,
                discount_percentage,
                discount_amount,
                created_at
            ) 
            VALUES (
            :foreign_currency_purchased,
            :foreign_currency_exchange_rate,
            :surcharge_percentage,
            :surcharge_amount,
            :foreign_currency_purchased_amount,
            :amount_paid_usd,
            :discount_percentage,
            :discount_amount,
            :created_at
            )', $param
        );
    }
}
