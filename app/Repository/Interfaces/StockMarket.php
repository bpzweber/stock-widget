<?php

namespace App\Repository\Interfaces;

interface StockMarket
{
    public function getRandomMostActiveCompanyStockPrice(): array;
}