<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\Transaction; 
use App\Models\Customer; 
use Carbon\Carbon;
use Exception;

class AsaasService implements AsaasServiceContract
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function createCustomerRequest(Customer $customer): array|Exception
    {
        $body = [
            'name' => $customer->name,
            'cpfCnpj' => $customer->document,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'postalCode' => $customer->postal_code,
            'addressNumber' => $customer->address_number,
        ];

        $data = $this->makeRequest($body, '/v3/customers');

        if (isset($data['error'])) {
            throw new Exception($data['message']);
        }

        return $data;
    }

    public function createTransactionRequest(Transaction $transaction, array $request): array|Exception
    {
        if ($transaction->type == Transaction::TYPE_CREDIT_CARD) {
            $data = $this->createCreditCardTransaction($transaction, $request);
        }

        if ($transaction->type == Transaction::TYPE_BOLETO) {
            $data = $this->createBoletoTransaction($transaction);
        }

        if ($transaction->type == Transaction::TYPE_PIX) {
            $data = $this->createPixTransaction($transaction);
        }

        if (isset($data['error'])) {
            throw new Exception($data['message']);
        }

        return $data;
    }

    public function createBoletoTransaction(Transaction $transaction): array
    {
        $date = Carbon::now();
        $date = $date->addDays(2);

        $body = [
            'customer' => $transaction->customer->asaas_customer_id,
            'billingType' => Transaction::TYPE_BOLETO,
            'value' => $transaction->value,
            'dueDate' => $date->format('Y-m-d'),
        ];

        return $this->makeRequest($body, '/v3/payments');
    }

    public function createPixTransaction(Transaction $transaction): array
    {
        $date = Carbon::now();
        $date = $date->addDays(2);

        $body = [
            'customer' => $transaction->customer->asaas_customer_id,
            'billingType' => Transaction::TYPE_PIX,
            'value' => $transaction->value,
            'dueDate' => $date->format('Y-m-d'),
        ];

        $resultTransaction = $this->makeRequest($body, '/v3/payments');

        $resultData = [];
        if (isset($resultTransaction['id'])) {
            $resultData = $this->makeRequest([], '/v3/payments/'.$resultTransaction['id'].'/pixQrCode', 'GET');
        }

        return array_merge($resultTransaction, $resultData);
    }

    public function createCreditCardTransaction(Transaction $transaction, array $request): array
    {
        $date = Carbon::now();
        $date = $date->addDays(2);

        $body = [
            'customer' => $transaction->customer->asaas_customer_id,
            'billingType' => Transaction::TYPE_CREDIT_CARD,
            'value' => $transaction->value,
            'dueDate' => $date->format('Y-m-d'),
            'creditCard' => [
                'holderName' => $request['holder_name'],
                'number' =>  $request['number'],
                'expiryMonth' =>  $request['expiry_month'],
                'expiryYear' =>  $request['expiry_year'],
                'ccv' =>  $request['ccv'],
            ],
            'creditCardHolderInfo' => [
                'name' => $transaction->customer->name,
                'email' => $transaction->customer->email,
                'cpfCnpj' => $transaction->customer->document,
                'postalCode' => $transaction->customer->postal_code,
                'addressNumber' => $transaction->customer->address_number,
                'phone' => $transaction->customer->phone,
            ]
        ];

        return $this->makeRequest($body, '/v3/payments');
    }

    public function makeRequest(array $body, string $url, string $type = 'POST'): array
    {
        $requestParams =[
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('ASAAS_API_KEY', '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwNjc5Nzc6OiRhYWNoXzI2MzI0MzI3LWEwNDgtNDUwNi04NjJkLWIzZDE4YzNlNzJjYQ==')
            ], 
        ];

        if (count($body) > 0) {
            $requestParams['json'] = $body;
        }

        try {
            $response = $this->client->request(
                $type, 
                env('ASAAS_API_URL', 'https://sandbox.asaas.com/api').$url, 
                $requestParams
            );

            $response = $response->getBody()->getContents();

            return json_decode($response, true);
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }
}