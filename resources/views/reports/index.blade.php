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
        <div class="col-lg-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">-- All Status --</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control" placeholder="Search..">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ request('description') }}" class="form-control" placeholder="Search..">
        </div>

        <div class="row g-1 mb-0 mt-2">
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

<div class="table-responsive">
    <table class="table table-bordered" style="width:100%;">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:7%">Type</th>
                <th style="width:15%">Document No.</th>
                <th style="width:25%">Description</th>
                <th style="width:10%">Department</th>
                <th style="width:10%">Amount</th>
                <th style="width:5%">Curr</th>
                <th style="width:15%">Rekening Tujuan</th>
                <th style="width:10%">Invoice Date</th>
                <th style="width:10%">Payment Date</th>
            </tr>
        </thead>
        <tbody>
        @if(count($finances) > 0)
            @foreach ($finances as $finance)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    {{$finance->type}}
                </td>
                <td style="word-break:break-word;">{{ $finance->doc_no }}</td>
                <td style="word-break:break-word;">{{ $finance->description }}</td>
                <td>{{ $finance->dept->nama ?? '-' }}</td>
                <td class="text-end">{{ number_format($finance->total_amount, 2) }}</td>
                <td>{{ $finance->matauang->nama ?? '-' }}</td>
                <td style="word-break:break-word;">
                    @if($finance->nama_rekening_tujuan)
                        <strong>{{ $finance->nama_rekening_tujuan }}</strong><br>
                        <small>{{ $finance->bank->nama ?? '' }} - {{ $finance->no_rek_tujuan }}</small>
                    @else
                        {{ $finance->rektujuan->nama ?? '-' }}
                    @endif
                </td>
                <td style="white-space:nowrap;">{{ $finance->invoice_date ? date('d-m-Y', strtotime($finance->invoice_date)) : '-' }}</td>
                <td style="white-space:nowrap;">{{ $finance->payment_date ? date('d-m-Y', strtotime($finance->payment_date)) : '-' }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">Data tidak ditemukan</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

{{ $finances->links('pagination::bootstrap-5') }}

@endsection
