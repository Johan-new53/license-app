@extends('layouts.app')
@section('content')

<div class="row">

    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Payment</h2>
    </div>

<form action="{{ route('payments.index') }}" method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-2">
            <label class="form-label">Payment Date</label>
            <input type="date" 
            name="payment_date" 
            value="{{ request('payment_date', now()->format('Y-m-d')) }}"  
            class="form-control"
            onchange="this.form.submit()">                    
            
        </div>

        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

<div class="d-flex gap-2">
    <form id="paidForm" action="{{ route('payments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="payment_date" value="{{ request('payment_date') }}">
        <button type="submit" class="btn btn-success">
            Update Status ke Paid
        </button>
    </form>

  
    
    <a href="{{ route('payments.export', ['payment_date' => request('payment_date', now()->format('Y-m-d'))]) }}" class="btn btn-success">
    Export Approved 2 To Excel
    </a>
    <a href="{{ route('payments.export_paid', ['payment_date' => request('payment_date', now()->format('Y-m-d'))]) }}" class="btn btn-success">
    Export Paid To Excel
    </a>

    

</div>

</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        {{ $message }}
    </div>
@endif

@if(isset($payments) && count($payments) > 0)
@php
    $i = ($payments->currentPage() - 1) * $payments->perPage();
@endphp
@endif
<br>
<hr class="mt-0">

<div class="table-responsive">
    <table class="table table-bordered" style="width:100%;">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:8%">Invoice Date</th>
                <th style="width:8%">Created Date</th>
                <th style="width:15%">Nama</th>
                <th style="width:3%">Top</th>
                <th style="width:8%">Due Date</th>
                <th style="width:8%">Type</th>
                <th style="width:15%">Document No.</th>
                <th style="width:25%">Description</th>
                <th style="width:7%">Status</th>
                <th style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($payments) && count($payments) > 0)
        @foreach ($payments as $payment)
        <tr>
            <td>{{ ++$i }}</td>
            <td style="white-space:nowrap;">{{ $payment->invoice_date ? $payment->invoice_date->format('d-m-Y') : '-' }}</td>
            <td style="white-space:nowrap;">{{ $payment->created_at->format('d-m-Y') }}</td>
            <td style="word-break:break-word;">{{ $payment->nama_payable }}</td>
            <td>{{ $payment->top_hari }}</td>            
            <td style="white-space:nowrap;">{{ $payment->due_date ? $payment->due_date->format('d-m-Y') : '-' }}</td>
            <td style="white-space:nowrap;">{{ $payment->type }}</td>
            <td style="word-break:break-word;">{{ $payment->doc_no }}</td>
            <td style="word-break:break-word;">{{ $payment->description }}</td>
            <td style="white-space:nowrap;">{{ $payment->status }}</td>
            <td>
                <form action="" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('payments.show',$payment->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                </form>
            </td>
        </tr>
        @endforeach
        @else
            <tr>
                <td colspan="11" class="text-center">
                    Silakan pilih Payment Date lalu klik Filter
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<br>

@if(isset($payments) && count($payments) > 0)
{{ $payments->links('pagination::bootstrap-5') }}
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('paidForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Konfirmasi Payment',
        text: 'Apakah Anda yakin ingin mengubah status prf menjadi PAID?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });

});
</script>



@endsection

