@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Bank Management</h2>
        @can('bank-create')
            <a class="btn btn-success btn-sm" href="{{ route('bank.create') }}">
                <i class="fa fa-plus"></i> Create New Bank
            </a>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="100px">No</th>
            <th>Name</th>
            <th width="100px" class="text-center">Valid</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bank as $key => $d)
        <tr>

            <td>{{ ++$i }}</td>
            <td>{{ $d->nama }}</td>
            <td class="text-center">
                @if($d->valid == 1)
                    <i class="fa-solid fa-check-circle text-success"></i>
                @else
                    <i class="fa-solid fa-times-circle text-danger"></i>
                @endif
            </td>
            <td>
                @can('bank-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('bank.edit', $d->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $bank->links('pagination::bootstrap-5') !!}

@endsection
