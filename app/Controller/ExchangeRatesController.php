<?php

namespace App\Controller;

use App\Repository\ExchangeRatesRepository;

/**
 * Class ImportDataController
 * @package App\Controller
 */
class ExchangeRatesController
{

    /**
     * @var ExchangeRatesRepository
     */
    private $repo;

    /**
     * ImportRatesController constructor.
     * @param ExchangeRatesRepository $exchangeRatesRepository
     */
    public function __construct(ExchangeRatesRepository $exchangeRatesRepository)
    {
        $this->repo = $exchangeRatesRepository;
    }

    /**
     * Method for importing exchange rates.
     */
    public function importExchangeRates()
    {
        $exchangeRatesData = $this->getExchangeRates();

        if ($exchangeRatesData['success'] && $this->repo->saveExchangeRates($exchangeRatesData['quotes'])) {
            echo "Good Job!";

            return;
        }

        echo "Error! Please contact your admin!";
    }

    /**
     * Method for getting exchange rates from apilayer.
     *
     * @return array
     */
    private function getExchangeRates()
    {
        $access_key = 'bf46ea639317b4bd56c750b486103d9b';

        // Initialize CURL:
        $ch = curl_init('http://apilayer.net/api/live?access_key=' . $access_key . '&currencies=JPY,EUR,GBP');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        return $exchangeRates;
    }
}
