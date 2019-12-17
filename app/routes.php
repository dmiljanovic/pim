<?php

use App\Route;
use App\Repository\HomeRepository;
use App\Controller\HomeController;
use App\Controller\ExchangeRatesController;
use App\Repository\ExchangeRatesRepository;

Route::set('index.php', function () {
    $homeCtrl = new HomeController(new HomeRepository());
    $homeCtrl->index();
});

Route::set('save-data', function () {
    $homeCtrl = new HomeController(new HomeRepository());
    header("Content-type: application/json; charset=utf-8");
    $homeCtrl->saveData();
});

Route::set('import-rates', function () {
    $ctrl = new ExchangeRatesController(new ExchangeRatesRepository());
    $ctrl->importExchangeRates();
});
