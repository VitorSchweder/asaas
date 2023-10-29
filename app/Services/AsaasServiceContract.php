<?php
namespace App\Services;
use App\Models\Transaction; 
use App\Models\Customer; 
use Exception;

interface AsaasServiceContract
{
    public function makeRequest(array $body, string $url, string $type): array;
    public function createCreditCardTransaction(Transaction $transaction, array $request): array;
    public function createPixTransaction(Transaction $transaction): array;
    public function createBoletoTransaction(Transaction $transaction): array;
    public function createTransactionRequest(Transaction $transaction, array $request): array|Exception;
    public function createCustomerRequest(Customer $customer): array|Exception;
}