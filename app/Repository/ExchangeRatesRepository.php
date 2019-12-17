<?php

namespace App\Repository;

use App\Database\Db;
use Analog\Analog;

/**
 * Class ImportDataRepository
 * @package App\Repository
 */
class ExchangeRatesRepository extends Db
{
    /**
     * Method for saving exchange rates in db.
     *
     * @param array $quotes
     * @return bool
     */
    public function saveExchangeRates($quotes)
    {
        $pdo = self::con();

        $pdo->beginTransaction();

        foreach ($quotes as $k => $v) {
            $param = [
                'code'      => $k,
                'rate'      => round($v, 4),
                'rate_2'    => round($v, 4)
            ];

            try {
                $res = self::saveData('
                    INSERT INTO exchange_rates
                    (code, rate)
                    VALUES
                    (:code, :rate)
                    ON DUPLICATE KEY UPDATE rate = :rate_2', $param
                );

                if (!$res) {
                    $pdo->rollBack();

                    return false;
                }
            } catch (\Exception $exception) {
                Analog::log('Error while saving exchange data: ' . $exception);
                $pdo->rollBack();

                return false;
            }
        }

        $pdo->commit();

        return true;
    }

    /**
     * Method for getting all exchange rates.
     *
     * @return array
     */
    public function getRates()
    {
        return self::getData('SELECT * FROM exchange_rates');
    }
}
