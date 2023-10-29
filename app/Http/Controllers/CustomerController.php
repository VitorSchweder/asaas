<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Repositories\Customers\CustomerRepositoryContract;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Log;
use App\Services\AsaasService;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    private CustomerRepositoryContract $customerRepository;
    private AsaasService $asaasService;

    public function __construct(CustomerRepositoryContract $customerRepositoryContract) 
    {
        $this->customerRepository = $customerRepositoryContract;
        $this->asaasService = new AsaasService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $customers = $this->customerRepository->paginate();        
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        try {
            $createdCustomer = $this->customerRepository->store($request->all());
            $createdCustomerInAsaas = $this->asaasService->createCustomerRequest($createdCustomer);

            $this->customerRepository->updateById([
                        'asaas_customer_id' => $createdCustomerInAsaas['id']
                    ], $createdCustomer->id);
        } catch (\Exception $exception) {
            Log::error('Internal error', [
                'exception' => $exception->getMessage(),
                'code' => 'customer_store'
            ]);

            return redirect()->route('customers.index')                                                                                                       
                ->with('error', 'Erro ao salvar o Cliente, consulte os logs para mais detalhes.');
        }

        return redirect()->route('customers.index')                                                                                                       
            ->with('success', 'Cliente criado com sucesso.');
    }
}
