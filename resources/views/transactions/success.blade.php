@extends('transactions.layouts')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">
            Transação concluída com sucesso!
        </div>

        @if ($createdTransactionInAsaas['billingType'] == 'PIX')
            <div class="text-center">
                <h2>QrCode:</h1>
                <img src="data:image/png;base64, {{ $createdTransactionInAsaas['encodedImage'] }}" alt="Image Preview" />

                <h2>Código:</h1>
                <p>{{ $createdTransactionInAsaas['payload'] }}</p>
            </div>
        @endif

        @if ($createdTransactionInAsaas['billingType'] == 'BOLETO')
            <div class="text-center">
                <h2>Link do do Boleto:</h1>
                <a href="{{ $createdTransactionInAsaas['bankSlipUrl'] }}" class="link-success" target="_blank">{{ $createdTransactionInAsaas['bankSlipUrl'] }}</a>
            </div>
        @endif


        @if ($createdTransactionInAsaas['billingType'] == 'CREDIT_CARD')
            @if ($createdTransactionInAsaas['status'] == 'CONFIRMED')
                <div class="text-center">
                    <h2>Pagamento realizado com sucesso!</h1>
                </div>
            @else 
                <div class="text-center">
                    <h2>Infelizmente seu pagamento não foi aprovado!</h1>
                </div>
            @endif
        @endif

        <a href="{{ route('transactions.index') }}" class="link-info mt-5">Voltar para listagem de transações</a>
    </div>    
</div>
    
@endsection