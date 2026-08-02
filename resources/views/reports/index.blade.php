@extends('layouts.app')

@section('content')

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>PRF Report</h2>
        <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success btn-sm">
            <i class="fa fa-file-excel"></i> Export Excel
        </a>
    </div>
</form>

<form action="{{ route('reports.index') }}" method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (From)</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (To)</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-control">
                <option value="">-- All Type --</option>
                <option value="hardcopy" {{ request('type') == 'hardcopy' ? 'selected' : '' }}>hardcopy</option>
                <option value="softcopy" {{ request('type') == 'softcopy' ? 'selected' : '' }}>softcopy</option>
                <option value="automate" {{ request('type') == 'automate' ? 'selected' : '' }}>automate</option>
                <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>digital</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control" placeholder="Search..">
        </div>
        <div class="col-lg-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ request('description') }}" class="form-control" placeholder="Search..">
        </div>

        <div class="col-12 mt-2">
            <label class="form-label">Status</label>
            <select name="status[]" class="form-control select2-status" multiple="multiple">
                <option value="requested" {{ is_array(request('status')) && in_array('requested', request('status')) ? 'selected' : '' }}>Requested</option>
                <option value="approved 1" {{ is_array(request('status')) && in_array('approved 1', request('status')) ? 'selected' : '' }}>Approved 1</option>
                <option value="approved 2" {{ is_array(request('status')) && in_array('approved 2', request('status')) ? 'selected' : '' }}>Approved 2</option>
                <option value="rejected 1" {{ is_array(request('status')) && in_array('rejected 1', request('status')) ? 'selected' : '' }}>Rejected 1</option>
                <option value="rejected 2" {{ is_array(request('status')) && in_array('rejected 2', request('status')) ? 'selected' : '' }}>Rejected 2</option>
                <option value="paid" {{ is_array(request('status')) && in_array('paid', request('status')) ? 'selected' : '' }}>Paid</option>
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
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary w-100">
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
    $i = ($finances->currentPage() - 1) * $finances->perPage();
@endphp
<hr class="mt-0">

<div class="table-responsive shadow-sm border rounded mb-3" style="overflow: auto; max-height: 70vh;">
    <table class="table table-bordered table-striped table-hover text-nowrap mb-0" style="min-width: 2800px; font-size: 13px;">
        <thead class="table-light text-center align-middle" style="position: sticky; top: 0; z-index: 2; background-color: #f8f9fa;">
            <tr>
                <th style="width:40px;">No</th>
                <th>TYPE</th>
                <th>RECEIPT DATE INVOICE FROM DIVISION</th>
                <th>UNIT HOSPITALS</th>
                <th>SUPPLIER NAME</th>
                <th>Invoice Date</th>
                <th>Document No</th>
                <th>DESCRIPTION</th>
                <th>STATUS</th>
                <th>PAYMENT TERM</th>
                <th>PO/AGREEMENT NO</th>
                <th>PO/AGREEMENT CATEGORY</th>
                <th>DEPT</th>
                <th>CURRENCY</th>
                <th>Amount</th>
                <th>PPN (IDR)</th>
                <th>KURS / Rupiah</th>
                <th>COURIER SERVICE/OTHERS</th>
                <th>WITHHOLDING TAX (PPh)</th>
                <th>GRAND TOTAL IDR</th>
            </tr>
        </thead>
        <tbody>
        @if(count($finances) > 0)
            @foreach ($finances as $finance)
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td><span class="badge bg-primary text-uppercase">{{ $finance->type }}</span></td>
                <td>{{ $finance->created_at ? $finance->created_at->format('d-m-Y') : '-' }}</td>
                <td>{{ $finance->rek_sumber->nama ?? '-' }}</td>
                <td>{{ $finance->payableto->nama ?? '-' }}</td>
                <td>{{ $finance->invoice_date ? date('d-m-Y', strtotime($finance->invoice_date)) : '-' }}</td>
                <td style="min-width: 250px; width: 300px; white-space: normal; word-break: break-word;">
                    <strong>{{ $finance->doc_no }}</strong>
                </td>
                <td style="min-width: 400px; width: 500px; white-space: normal; word-break: break-word;">
                    {{ $finance->description }}
                </td>
                <td><span class="badge bg-info text-dark">{{ $finance->status }}</span></td>
                <td>{{ $finance->payment_term ?? '-' }}</td>
                <td>{{ $finance->po_no ?? '-' }}</td>
                <td>{{ $finance->category->nama ?? '-' }}</td>
                <td>{{ $finance->dept->nama ?? '-' }}</td>
                <td>{{ $finance->matauang->nama ?? '-' }}</td>
                <td class="text-end">{{ number_format($finance->dpp, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($finance->nilai_ppn, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-end">{{ number_format(($finance->pph * -1), 0, ',', '.') }}</td>
                <td class="text-end fw-bold">{{ number_format($finance->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="20" class="text-center py-4 text-muted">Data tidak ditemukan</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

{{ $finances->links('pagination::bootstrap-5') }}

<script>
    $(document).ready(function() {
        $('.select2-status').select2({
            placeholder: "-- All Status --",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endsection
