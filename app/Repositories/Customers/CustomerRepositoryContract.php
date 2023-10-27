<?php

namespace App\Repositories\Customers;

use App\Repositories\BaseRepositoryContract;

interface CustomerRepositoryContract extends BaseRepositoryContract {
    public function all();
}