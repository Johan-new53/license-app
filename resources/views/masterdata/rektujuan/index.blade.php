@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Rekening Tujuan Management</h2>
        @can('rektujuan-create')
            <a class="btn btn-success btn-sm" href="{{ route('rektujuan.create') }}">
                <i class="fa fa-plus"></i> Create New Rekening Tujuan
            </a>
        @endcan
    </div>
</div>

<form action="{{ route('rektujuan.index') }}" method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" name="nama" value="{{ request('nama') }}" class="form-control" placeholder="Search name...">
        </div>
        <div class="col-md-2">
            <label class="form-label">No Rekening</label>
            <input type="text" name="norek" value="{{ request('norek') }}" class="form-control" placeholder="Search account...">
        </div>
        <div class="col-md-2">
            <label class="form-label">Bank</label>
            <input type="text" name="bank" value="{{ request('bank') }}" class="form-control" placeholder="Search bank...">
        </div>
        <div class="col-md-2">
            <label class="form-label">Valid </label>
            <select name="valid" class="form-select">
                <option value="">-- Semua --</option>
                <option value="1" {{ request('valid') == '1' ? 'selected' : '' }}>Valid</option>
                <option value="0" {{ request('valid') == '0' ? 'selected' : '' }}>Invalid</option>
            </select>
        </div>
        <div class="col-md-2">
            <div class="row g-1">
                <div class="col-6">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-6">
                    <a href="{{ route('rektujuan.index') }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<hr class="mt-0">
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center" width="100px">No</th>
            <th class="text-center">Name</th>
            <th class="text-center">No Rekening</th>
            <th class="text-center">Bank</th>
            <th class="text-center" width="100px">Valid</th>
            <th class="text-center" width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rektujuan as $key => $d)
        <tr>

            <td class="text-center">{{ ++$i }}</td>
            <td>{{ $d->nama }}</td>
            <td class="text-center">{{ $d->norek }}</td>
            <td class="text-center">{{ $d->bank }}</td>
            <td class="text-center">
                @if($d->valid == 1)
                    <i class="fa-solid fa-check-circle text-success"></i>
                @else
                    <i class="fa-solid fa-times-circle text-danger"></i>
                @endif
            </td>
            <td class="text-center">
                @can('dept-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('rektujuan.edit', $d->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $rektujuan->appends(request()->query())->links('pagination::bootstrap-5') !!}

@endsection
