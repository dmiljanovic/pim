<?php

namespace App\Controller;

use App\Helpers\Mail;
use App\Repository\HomeRepository;
use App\Repository\ExchangeRatesRepository;
use Analog\Analog;
use Analog\Handler\File;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends BaseController
{
    /**
     * @var array
     */
    private $surcharge = [
        'JPY' => 7.5,
        'GBP' => 5,
        'EUR' => 5
    ];

    /**
     * @var HomeRepository
     */
    private $repo;

    /**
     * HomeController constructor.
     * @param HomeRepository $homeRepository
     */
    public function __construct(HomeRepository $homeRepository)
    {
        Analog::handler(File::init('/tmp/php.log'));
        $this->repo = $homeRepository;
    }

    /**
     * Method for creating index view.
     */
    public function index()
    {
        try {
            $exchangeRatesRepo = new ExchangeRatesRepository();
            $rates = $exchangeRatesRepo->getRates();
        } catch (\Exception $exception) {
            Analog::log('Error while getting exchange rates from db: ' . $exception);
        }

        $this->createView('index', $rates);
    }

    /**
     * Method for saving data
     */
    public function saveData()
    {
        $dataToBeSaved = $this->prepareDataForSaving();
        $data = [];

        try {
            $this->repo->saveOrder($dataToBeSaved);
            $data['success'] = true;
            $data['message'] = "Successfully created order!";
        } catch (\Exception $exception) {
            Analog::log('Error while saving exchange rates from db: ' . $exception);
            $data['success'] = false;
            $data['message'] = "Error while creating order, please contact your admin!";
        }

        if ($data['success'] && $this->getPurchasedCurrency() === 'GBP') {
            try {
                Mail::send();
            } catch (\Exception $exception) {
                Analog::log('Error while sending mail: ' . $exception);
            }
        }

        echo json_encode($data);
    }

    /**
     * Method for getting data ready for saving.
     *
     * @return array
     */
    private function prepareDataForSaving()
    {
        $dataToBeSaved = [];

        $dataToBeSaved['foreign_currency_purchased']        = $this->getPurchasedCurrency();
        $dataToBeSaved['foreign_currency_exchange_rate']    = $this->sanitizeData($_REQUEST['rate']);
        $dataToBeSaved['surcharge_percentage']              = $this->surcharge[$this->getPurchasedCurrency()];
        $dataToBeSaved['surcharge_amount']                  = $this->getSurchargeAmount();
        $dataToBeSaved['foreign_currency_purchased_amount'] = $this->sanitizeData($_REQUEST['amount_to_buy']);
        $dataToBeSaved['discount_percentage']               = $this->getDiscountPercentage();
        $dataToBeSaved['discount_amount']                   = $this->getDiscountAmount();
        $dataToBeSaved['amount_paid_usd']                   = $this->getFullPayedInUSD();
        $dataToBeSaved['created_at']                        = date('Y-m-d H:i:s');

        return $dataToBeSaved;
    }

    /**
     * Method for getting purchased currency.
     *
     * @return string
     */
    private function getPurchasedCurrency()
    {
        $quote = $this->sanitizeData($_REQUEST['quote_string']);

        return substr($quote, -3);
    }

    /**
     * Method for getting surcharge amount.
     *
     * @return float|int
     */
    private function getSurchargeAmount()
    {
        $amountToPay = $this->sanitizeData($_REQUEST['amount_to_pay']);

        return $amountToPay * $this->surcharge[$this->getPurchasedCurrency()] / 100;
    }

    /**
     * Method for getting discount percentage.
     *
     * @return int|null
     */
    private function getDiscountPercentage()
    {
        if ($this->getPurchasedCurrency() === 'EUR') {
            return 2;
        }

        return null;
    }

    /**
     * Method for getting discount amount.
     *
     * @return float|int|null
     */
    private function getDiscountAmount() {
        if ($this->getDiscountPercentage()) {
            return $this->sanitizeData($_REQUEST['amount_to_pay']) * $this->getDiscountPercentage() / 100;
        }

        return null;
    }

    /**
     * Method for calculating full payed in USD.
     *
     * @return float|int|string
     */
    private function getFullPayedInUSD()
    {
        $base = $this->sanitizeData($_REQUEST['amount_to_pay']);
        $base = round($base, 4) + $this->getSurchargeAmount();

        if ($this->getDiscountPercentage()) {
            $base -= $base * $this->getDiscountPercentage() / 100;
        }

        return $base;
    }


    /**
     * Method for sanitizing input data.
     *
     * @param string $data
     * @return string
     */
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}
