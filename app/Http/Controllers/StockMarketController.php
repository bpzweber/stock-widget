<?php

namespace App\Http\Controllers;

use App\Repository\Interfaces\StockMarket;

class StockMarketController extends Controller
{
    public function getRandomMostActiveCompanyStockPrice(StockMarket $stockMarket)
    {
        return $stockMarket->getRandomMostActiveCompanyStockPrice();
    }
}
