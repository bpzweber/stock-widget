<?php

namespace App\Repository;

use Zttp\PendingZttpRequest;

abstract class ApiRequest
{
    protected $zttp;

    public function __construct(PendingZttpRequest $zttp)
    {
        $this->zttp = $zttp;
    }
}