@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Data Management</h2>

        <div class="d-flex gap-2 mt-2 mt-lg-0">
            @can('payable-create')
                <a class="btn btn-success btn-sm"
                   href="{{ route('payable.create', ['type' => request('type', 'hardcopy')]) }}">
                    <i class="fa fa-plus"></i> Create New Data
                </a>

                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fa fa-upload"></i> Import Data
                </button>

                <a href="{{ route('payable.export', ['type' => $type]) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-download"></i> Export Data
                </a>
            @endcan

            {{--
            <form id="syncForm" action="{{ route('payable.sync') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="type" value="{{ request('type', 'hardcopy') }}">
                <button type="submit" id="syncButton" class="btn btn-warning btn-sm">
                    <span class="sync-btn-text">
                        <i class="fa fa-sync"></i> Sync data from D365
                    </span>
                    <span class="sync-btn-loading d-none">
                        <i class="fa fa-spinner fa-spin"></i> Syncing...
                    </span>
                </button>
            </form>
            --}}
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('payable.index', ['type' => 'hardcopy']) }}"
       class="btn btn-sm {{ request('type','hardcopy') == 'hardcopy' ? 'btn-primary' : 'btn-outline-primary' }}">
        Hardcopy
    </a>

    <a href="{{ route('payable.index', ['type' => 'softcopy']) }}"
       class="btn btn-sm {{ request('type') == 'softcopy' ? 'btn-primary' : 'btn-outline-primary' }}">
        Softcopy
    </a>

    <a href="{{ route('payable.index', ['type' => 'automate']) }}"
       class="btn btn-sm {{ request('type') == 'automate' ? 'btn-primary' : 'btn-outline-primary' }}">
        Automate
    </a>

    <a href="{{ route('payable.index', ['type' => 'digital']) }}"
       class="btn btn-sm {{ request('type') == 'digital' ? 'btn-primary' : 'btn-outline-primary' }}">
        Digital
    </a>
</div>

<form action="{{ route('payable.index') }}" method="GET" class="mb-3">
    <input type="hidden" name="type" value="{{ request('type', 'hardcopy') }}">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" name="nama" value="{{ request('nama') }}" class="form-control" placeholder="Search name...">
        </div>
        <div class="col-md-2">
            <label class="form-label">Vendor Account</label>
            <input type="text" name="vendor_account" value="{{ request('vendor_account') }}" class="form-control" placeholder="Search account...">
        </div>
        <div class="col-md-2">
            <label class="form-label">TOP (hari)</label>
            <input type="number" name="hari" value="{{ request('hari') }}" class="form-control" placeholder="Days...">
        </div>
        <div class="col-md-2">
            <label class="form-label">Valid</label>
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
                    <a href="{{ route('payable.index', ['type' => request('type', 'hardcopy')]) }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="alert alert-warning">
        <strong>Beberapa baris tidak dapat diproses:</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('import_errors') as $importError)
                <li>{{ $importError }}</li>
            @endforeach
        </ul>
    </div>
@endif

<hr class="mt-0">

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center" width="100px">No</th>
            <th>Name</th>
            <th class="text-center">Vendor Account</th>
            <th class="text-center">TOP (hari)</th>
            <th class="text-center" width="100px">Valid</th>
            <th class="text-center" width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($payable as $key => $d)
        <tr>
            <td class="text-center">{{ ++$i }}</td>
            <td>{{ $d->nama }}</td>
            <td class="text-center">{{ $d->vendor_account }}</td>
            <td class="text-center">{{ $d->hari }}</td>
            <td class="text-center">
                @if($d->valid == 1)
                    <i class="fa-solid fa-check-circle text-success"></i>
                @else
                    <i class="fa-solid fa-times-circle text-danger"></i>
                @endif
            </td>
            <td class="text-center">
                @can('payable-edit')
                    <a class="btn btn-primary btn-sm"
                       href="{{ route('payable.edit', ['payable' => $d->id, 'type' => request('type', 'hardcopy')]) }}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No data available</td>
        </tr>
        @endforelse
    </tbody>
</table>

{!! $payable->appends(request()->query())->links('pagination::bootstrap-5') !!}

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payable.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">
                            Format file: .xlsx atau .xls, maksimal 5 MB.
                        </small>
                        @error('file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        Upload Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
