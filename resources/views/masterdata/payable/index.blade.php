@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Payable Management</h2>
        @can('dept-create')
            <div class="d-flex gap-2 mt-2 mt-lg-0">
                @if(request('type') == 'softcopy')
                    <a class="btn btn-success btn-sm" href="{{ route('payable.create', ['type' => 'softcopy']) }}">
                        <i class="fa fa-plus"></i> Create New Payable
                    </a>
                @endif

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
            </div>
        @endcan
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
                    <a href="{{ route('payable.index', ['type' => request('type', 'hardcopy')]) }}" class="btn btn-secondary w-100">
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
            <th>Name</th>
            <th class="text-center">Vendor Account</th>
            <th class="text-center">TOP (hari)</th>
            <th class="text-center" width="100px">Valid</th>
            <th class="text-center" width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payable as $key => $d)
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
                @can('dept-edit')
                    @if(request('type') == 'softcopy')
                        <a class="btn btn-primary btn-sm" href="{{ route('payable.edit', $d->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    @endif
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $payable->appends(request()->query())->links('pagination::bootstrap-5') !!}

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const syncForm = document.getElementById('syncForm');
        const syncButton = document.getElementById('syncButton');

        if (syncForm) {
            syncForm.addEventListener('submit', function () {
                syncButton.disabled = true;
                syncButton.querySelector('.sync-btn-text').classList.add('d-none');
                syncButton.querySelector('.sync-btn-loading').classList.remove('d-none');
            });
        }
    });
</script>
@endpush
