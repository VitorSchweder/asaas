@extends('customers.layouts')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">Clientes</div>
            <div class="card-body">
                <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Novo Cliente</a>
                <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm my-2"><i class="bi bi-list"></i> Transações</a>

                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">CPF</th>
                        <th scope="col">CEP</th>
                        <th scope="col">Número do endereço</th>
                        <th scope="col">Telefone</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <th scope="row">{{ $customer->id }}</th>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->document }}</td>
                                <td>{{ $customer->postal_code }}</td>
                                <td>{{ $customer->address_number }}</td>
                                <td>{{ $customer->phone }}</td>
                            </tr>
                        @empty
                            <td colspan="6">
                                <span class="text-danger">
                                    <strong>Nenhum cliente encontrado.</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                  </table>

                  {{ $customers->links() }}
            </div>
        </div>
    </div>    
</div>
    
@endsection