<?php

namespace App\Repository;

use App\Repository\Interfaces\StockMarket;

class StockWidget extends ApiRequest implements StockMarket
{
    private function getMostActiveCompanies()
    {
        $requestUrl = 'https://financialmodelingprep.com/api/v3/stock/actives';

        return cache()->remember('most_active_companies', 86400, function () use ($requestUrl) {
            $response = $this->zttp->get($requestUrl)->json();

            return array_map(function ($stock) {
                $stock['updateTime'] = now()->format('H:i');
                return $stock;
            }, $response['mostActiveStock']);
        });
    }

    private function updateCache($key, $price, $updateTime)
    {
        $currentCachedMostActiveCompanies = cache('most_active_companies');

        $currentCachedMostActiveCompanies[$key]['price'] = $price;
        $currentCachedMostActiveCompanies[$key]['updateTime'] = $updateTime;


        cache()->put('most_active_companies', $currentCachedMostActiveCompanies);
    }

    private function getUpdatedTime($key, $company, $responsePrice)
    {
        if ($company['price'] == $responsePrice) {
            return $company['updateTime'];
        }

        $updateTime = now()->format('H:i');

        $this->updateCache($key, $responsePrice, $updateTime);

        return $updateTime;
    }

    public function getRandomMostActiveCompanyStockPrice(): array
    {
        $mostActiveCompanies = $this->getMostActiveCompanies();
        $randomMostActiveCompanyKey = array_rand($mostActiveCompanies);
        $randomMostActiveCompany = $mostActiveCompanies[$randomMostActiveCompanyKey];

        $requestUrl ='https://financialmodelingprep.com/api/v3/stock/real-time-price/' . $randomMostActiveCompany['ticker'];
        $response = $this->zttp->get($requestUrl)->json();

        $updatedTime = $this->getUpdatedTime(
            $randomMostActiveCompanyKey,
            $randomMostActiveCompany,
            $response['price']
        );

        return [
            'companyName' => $randomMostActiveCompany['companyName'],
            'currentPrice' => $response['price'],
            'updateTime' => $updatedTime
        ];
    }
}