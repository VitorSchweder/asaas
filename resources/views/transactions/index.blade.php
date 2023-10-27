@extends('transactions.layouts')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('message'))
            <div class="alert alert-info" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">Transações realizadas</div>
            <div class="card-body">
                <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Nova transação</a>
                <a href="{{ route('customers.index') }}" class="btn btn-primary btn-sm my-2"><i class="bi bi-list"></i> Clientes</a>

                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Data</th>
                        <th scope="col">Cliente</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transaction->id }}</th>
                            <td>{{ $transaction->status }}</td>
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>{{ $transaction->customer->name }}</td>
                        </tr>
                        @empty
                            <td colspan="6">
                                <span class="text-danger">
                                    <strong>Nenhuma transação encontrada.</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                  </table>

                  {{ $transactions->links() }}
            </div>
        </div>
    </div>    
</div>
    
@endsection