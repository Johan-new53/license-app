@extends('layouts.app')


@section('content')
<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Approval</h2>
    </div>
     <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">

        <h6>Level : {{ Auth::user()->level }}</h6>
    </div>



</form>
<form action="{{ route('approvals.index') }}" method="GET" class="mb-3">
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
                <option value="">-- Pilih --</option>
                <option value="hardcopy">hardcopy</option>
                <option value="softcopy">softcopy</option>
                <option value="automate">automate</option>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ request('description') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- Semua --</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row g-1 mb-0">
            <div class="col-10">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fa fa-search"></i> Filter
                </button>
            </div>

            <div class="col-2">
                <a href="{{ route('approvals.index') }}" class="btn btn-secondary w-100">
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
    $i = ($approvals->currentPage() - 1) * $approvals->perPage();
@endphp
<hr class="mt-0">

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Invoice Date</th>
            <th>Type</th>
            <th>Document No.</th>
            <th>Description</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($approvals as $approval)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $approval->invoice_date->format('d-m-Y') }}</td>
            <td>{{ $approval->type }}</td>
            <td>{{ $approval->doc_no }}</td>
            <td>{{ $approval->description }}</td>
            <td>{{ $approval->status }}</td>
            <td>
                <form action="" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('approvals.show',$approval->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>



                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
<br>


{{ $approvals->links('pagination::bootstrap-5') }}
@endsection


