<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\Transaction; 
use Carbon\Carbon;

class AsaasService 
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function createCustomerRequest($customer)
    {
        $body = [
            'name' => $customer->name,
            'cpfCnpj' => $customer->document,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'postalCode' => $customer->postal_code,
            'addressNumber' => $customer->address_number,
        ];

        return $this->makeRequest($body, '/v3/customers');
    }

    public function createTransactionRequest($transaction, $request)
    {
        if ($transaction->type == Transaction::TYPE_CREDIT_CARD) {
            return $this->createCreditCardTransaction($transaction, $request);
        }

        if ($transaction->type == Transaction::TYPE_BOLETO) {
            return $this->createBoletoTransaction($transaction, $request);
        }

        if ($transaction->type == Transaction::TYPE_PIX) {
            return $this->createPixTransaction($transaction, $request);
        }
    }

    private function createBoletoTransaction($transaction, $request)
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

    private function createPixTransaction($transaction, $request)
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
        $resultData = $this->makeRequest([], '/v3/payments/'.$resultTransaction['id'].'/pixQrCode', 'GET');

        return array_merge($resultTransaction, $resultData);
    }

    private function createCreditCardTransaction($transaction, $request)
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

    private function makeRequest(array $body, string $url, $type = 'POST'): array
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
                env('ASASS_API_URL', 'https://sandbox.asaas.com/api').$url, 
                $requestParams
            );

            $response = $response->getBody()->getContents();

            return json_decode($response, true);
        } catch (\Exception $exception) {
            Log::error('Internal error', [
                'exception' => $exception->getMessage(),
                'code' => 'asaas_service_request'
            ]);

            return [];
        }
    }
}