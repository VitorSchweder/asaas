<?php

namespace App\Repositories\Customers;

use App\Models\Customer;
use App\Repositories\BaseRepository;

class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepositoryContract
{
    protected $model;

    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    public function all()
    {
        return $this->model->orderBy('name')->get();
    }
}