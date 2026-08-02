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
                <option value="hardcopy" {{ request('type') == 'hardcopy' ? 'selected' : '' }}>hardcopy</option>
                <option value="softcopy" {{ request('type') == 'softcopy' ? 'selected' : '' }}>softcopy</option>
                <option value="automate" {{ request('type') == 'automate' ? 'selected' : '' }}>automate</option>
                <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>digital</option>
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Payable To</label>
            <input type="text" name="payable_to" value="{{ request('payable_to') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control">
        </div>
        <div class="col-lg-2">
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
                    <a href="{{ route('approvals.index') }}" class="btn btn-secondary w-100">
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
    $i = ($approvals->currentPage() - 1) * $approvals->perPage();
@endphp
<hr class="mt-0">

<div class="table-responsive">
    <table class="table table-bordered" style="width:100%;">
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:9%">Invoice Date</th>
                <th style="width:7%">Type</th>
                <th style="width:16%">Payable To</th>
                <th style="width:18%">Document No.</th>
                <th style="width:30%">Description</th>
                <th style="width:8%">Status</th>
                <th style="width:8%">Payment Date</th>
                <th style="width:8%">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($approvals as $approval)
        <tr>
            <td>{{ ++$i }}</td>
            <td style="white-space:nowrap;">
                {{ $approval->invoice_date ? $approval->invoice_date->format('d-m-Y') : '-' }}
            </td>
            <td style="white-space:nowrap;">{{ $approval->type }}</td>
            <td style="word-break:break-word;">
                {{ $approval->payableto->nama ?? '-' }}
            </td>
            <td style="word-break:break-word;">{{ $approval->doc_no }}</td>
            <td style="word-break:break-word;">{{ $approval->description }}</td>
            <td style="white-space:nowrap;">{{ $approval->status }}</td>
            <td style="white-space:nowrap;">
                {{ $approval->payment_date ? $approval->payment_date->format('d-m-Y') : '-' }}
            </td>

            <td>
                <form action="" method="POST" style="display:flex; flex-direction:column; gap:5px;">
                    @if($approval->type == 'digital')
                        <a class="btn btn-info btn-sm" href="{{ route('approvals.show',$approval->id) }}">
                            <i class="fa-solid fa-list"></i> Show
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route('digitals.edit', $approval->id) }}?source=approval_index">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                    @else
                        <a class="btn btn-info btn-sm" href="{{ route('approvals.show',$approval->id) }}">
                            <i class="fa-solid fa-list"></i> Show
                        </a>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<br>

{{ $approvals->links('pagination::bootstrap-5') }}

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


