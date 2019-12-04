<?php

namespace Tests\Unit;

use App\Repository\StockWidget;
use Zttp\PendingZttpRequest;
use Tests\TestCase;

class StockWidgetTest extends TestCase
{
    private $stockWidget;

    private $requiredProps = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->stockWidget = new StockWidget(new PendingZttpRequest());
        $this->requiredProps = [
            'companyName',
            'currentPrice',
            'updateTime'
        ];
    }

    /** @test */
    public function should_include_required_props()
    {
        $request = $this->stockWidget->getRandomMostActiveCompanyStockPrice();

        foreach ($this->requiredProps as $requiredProp) {
            $this->assertTrue(array_key_exists($requiredProp, $request));
        }
    }

    /** @test */
    public function most_active_companies_caches()
    {
        $this->stockWidget->getRandomMostActiveCompanyStockPrice();

        $this->assertCount(10, cache('most_active_companies'));
    }

    /** @test */
    public function requested_random_most_active_company_belongs_to_most_active_companies_cache()
    {
        $randomMostActiveCompany = $this->stockWidget->getRandomMostActiveCompanyStockPrice();

        $match = array_filter(cache('most_active_companies'), function ($company) use ($randomMostActiveCompany) {
            return
                $company['companyName'] == $randomMostActiveCompany['companyName'] &&
                $company['price'] == $randomMostActiveCompany['currentPrice'] &&
                $company['updateTime'] == $randomMostActiveCompany['updateTime'];
        });

        $this->assertEquals(1, count($match));
    }
}
