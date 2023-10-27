@extends('transactions.layouts')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Nova transação
                </div>
                <div class="float-end">
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">&larr; Voltar</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="customer_id" class="col-md-4 col-form-label text-md-end text-start">Cliente</label>
                        <div class="col-md-6">
                            <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach 
                            </select>
                            @if ($errors->has('customer_id'))
                                <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="value" class="col-md-4 col-form-label text-md-end text-start">Valor</label>
                        <div class="col-md-6">
                        <input type="text" onInput="mascaraMoeda(event);" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}">
                            @if ($errors->has('value'))
                                <span class="text-danger">{{ $errors->first('value') }}</span>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         s......,,m ,') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="type" class="col-md-4 col-form-label text-md-end text-start">Forma de pagamento</label>
                        <div class="col-md-6">
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="" selected>Selecione</option>
                                <option value="CREDIT_CARD">Cartão de crédito</option>
                                <option value="PIX">Pix</option>
                                <option value="BOLETO">Boleto</option>
                            </select>
                            @if ($errors->has('type'))
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            @endif
                        </div>
                    </div>
                    <div id="container-card-fields" class="md-12 d-none">
                        <div class="mb-3 row">
                            <label for="holder_name" class="col-md-4 col-form-label text-md-end text-start">Nome escrito no cartão</label>
                            <div class="col-md-6">
                            <input type="text" class="form-control @error('holder_name') is-invalid @enderror" id="holder_name" name="holder_name" value="{{ old('holder_name') }}">
                                @if ($errors->has('holder_name'))
                                    <span class="text-danger">{{ $errors->first('holder_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="number" class="col-md-4 col-form-label text-md-end text-start">Número</label>
                            <div class="col-md-6">
                            <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}">
                                @if ($errors->has('number'))
                                    <span class="text-danger">{{ $errors->first('number') }}</span>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              s......,,m ,') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="expiry_month" class="col-md-4 col-form-label text-md-end text-start">Mês de vencimento</label>
                            <div class="col-md-6">
                            <input type="text" class="form-control @error('expiry_month') is-invalid @enderror" id="expiry_month" name="expiry_month" value="{{ old('expiry_month') }}">
                                @if ($errors->has('expiry_month'))
                                    <span class="text-danger">{{ $errors->first('expiry_month') }}</span>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         s......,,m ,') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="expiry_year" class="col-md-4 col-form-label text-md-end text-start">Ano de vencimento</label>
                            <div class="col-md-6">
                            <input type="text" class="form-control @error('expiry_year') is-invalid @enderror" id="expiry_year" name="expiry_year" value="{{ old('expiry_year') }}">
                                @if ($errors->has('expiry_year'))
                                    <span class="text-danger">{{ $errors->first('expiry_year') }}</span>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             s......,,m ,') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="ccv" class="col-md-4 col-form-label text-md-end text-start">CCV</label>
                            <div class="col-md-6">
                            <input type="text" class="form-control @error('ccv') is-invalid @enderror" id="ccv" name="ccv" value="{{ old('ccv') }}">
                                @if ($errors->has('ccv'))
                                    <span class="text-danger">{{ $errors->first('ccv') }} </span>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    s......,,m ,') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-success" value="Enviar pagamento">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection