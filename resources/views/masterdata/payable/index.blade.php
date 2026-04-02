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
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center" width="100px">No</th>
            <th>Name</th>
            <th class="text-center">Vendor Account</th>
            <th class="text-center">TOP (hari)</th>
            <th width="100px" class="text-center">Valid</th>
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

{!! $payable->links('pagination::bootstrap-5') !!}

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
