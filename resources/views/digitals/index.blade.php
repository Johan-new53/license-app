@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
</div>

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Digital</h2>
        @can('digital-create')
            <a class="btn btn-success btn-sm" href="{{ route('digitals.create') }}">
                <i class="fa fa-plus"></i> Create New Digital
            </a>
        @endcan


    </div>


</form>
<form action="{{ route('digitals.index') }}" method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (From)</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (To)</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-lg-3">
            <label class="form-label">Payable To</label>
            <input type="text" name="payable_to" value="{{ request('payable_to') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
        </div>
        <div class="col-lg-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ request('description') }}" class="form-control">
        </div>

        <div class="col-12 mt-2">
            <label class="form-label">Status</label>
            <select name="status[]" class="form-control select2-status" multiple="multiple">
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" {{ is_array(request('status')) && in_array($st, request('status')) ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 mt-3">
            <div class="row g-2">
                <div class="col-10">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-2">
                    <a href="{{ route('digitals.index') }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        {{ $message }}
    </div>
@endif

@php
    $i = ($digitals->currentPage() - 1) * $digitals->perPage();
@endphp
<hr class="mt-0">

<div class="table-responsive">
<table class="table table-bordered" style="width:100%;">
    <thead>
        <tr class="align-middle">
            <th class="text-center" style="width:3%">No</th>
            <th class="text-center" style="width:8%">Invoice Date</th>
            <th style="width:12%">Payable To</th>
            <th style="width:18%">Document No.</th>
            <th style="width:35%">Description</th>
            <th class="text-center" style="width:8%">Status</th>
            <th class="text-center" style="width:12%">Due / Payment Date</th>
            <th class="text-center" style="width:8%">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($digitals as $digital)
        <tr class="align-middle">
            <td class="text-center">{{ ++$i }}</td>
            <td class="text-center" style="white-space:nowrap;">
                {{ $digital->invoice_date ? \Carbon\Carbon::parse($digital->invoice_date)->format('d-m-Y') : '-' }}
            </td>
            <td style="word-break:break-word;">
                {{ $digital->payableto->nama ?? null }}
            </td>
            <td style="word-break:break-word;">
                {{ $digital->doc_no }}
            </td>
            <td style="word-break:break-word;">
                {{ $digital->description }}
            </td>
            <td class="text-center" style="white-space:nowrap;">
                @php
                    $statusClass = match(strtolower($digital->status ?? '')) {
                        'paid' => 'bg-success text-white',
                        'approved 2' => 'bg-primary text-white',
                        'approved 1' => 'bg-info text-dark',
                        'requested' => 'bg-warning text-dark',
                        'rejected 1', 'rejected 2' => 'bg-danger text-white',
                        default => 'bg-secondary text-white',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $digital->status }}</span>
            </td>
            <td class="text-center" style="white-space:nowrap;">
                <strong>Due</strong><br>
                {{ in_array($digital->status, ['approved 2', 'paid']) && $digital->due_date ? \Carbon\Carbon::parse($digital->due_date)->format('d-m-Y') : '-' }}<br>
                <strong>Paid</strong><br>
                {{ $digital->payment_date ? \Carbon\Carbon::parse($digital->payment_date)->format('d-m-Y') : '-' }}
            </td>

            <td class="text-center">
                <form action="{{ route('digitals.destroy',$digital->id) }}" method="POST"
                      style="display:flex; flex-direction:column; gap:5px;">

                    <a class="btn btn-info btn-sm"
                       href="{{ route('digitals.show',$digital->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>

                    @can('digital-edit')
                        @if ($digital->status!='paid' && $digital->status!='approved 1' && $digital->status!='approved 2')
                        <a class="btn btn-primary btn-sm"
                           href="{{ route('digitals.edit',$digital->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        @endif
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('digital-delete')
                        @if ($digital->status!='paid' && $digital->status!='approved 1' && $digital->status!='approved 2')
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                        @endif
                    @endcan

                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<br>


{{ $digitals->links('pagination::bootstrap-5') }}

<script>
    $(document).ready(function() {
        $('.select2-status').select2({
            placeholder: "-- Pilih Status --",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection


