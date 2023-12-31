<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\View\View;
use App\Repositories\Transactions\TransactionRepositoryContract;
use App\Repositories\Customers\CustomerRepositoryContract;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TransactionRequest;
use App\Services\AsaasServiceContract;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    private TransactionRepositoryContract $transactionRepository;
    private CustomerRepositoryContract $customerRepository;
    private AsaasServiceContract $asaasService;

    public function __construct(
        TransactionRepositoryContract $transactionRepositoryContract,
        CustomerRepositoryContract $customerRepositoryContract,
        AsaasServiceContract $asaasServiceContract
    ) {
        $this->transactionRepository = $transactionRepositoryContract;
        $this->customerRepository = $customerRepositoryContract;
        $this->asaasService = $asaasServiceContract;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $transactions = $this->transactionRepository->paginate();        
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = $this->customerRepository->all();  
        return view('transactions.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request): View|RedirectResponse
    {
        try {
            $createdTransaction = $this->transactionRepository->store($request->all());
            $createdTransactionInAsaas = $this->asaasService->createTransactionRequest($createdTransaction, $request->all());

            $this->transactionRepository->updateById([
                'asaas_transaction_id' => $createdTransactionInAsaas['id'],
                'status' => $createdTransactionInAsaas['status'],
            ], $createdTransaction->id);

            return view('transactions.success', compact('createdTransactionInAsaas'));
        } catch (\Exception $exception) {
            Log::error('Internal error', [
                'exception' => $exception->getMessage(),
                'code' => 'transaction_store'
            ]);

            return redirect()->route('transactions.index')                                                                                                       
                ->with('error', 'Erro ao salvar transação, consulte os logs para mais detalhes.');
        }
    }
}
