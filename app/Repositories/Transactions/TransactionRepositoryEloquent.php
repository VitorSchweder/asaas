<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Repositories\BaseRepository;

class TransactionRepositoryEloquent extends BaseRepository implements TransactionRepositoryContract
{
    protected $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }
}