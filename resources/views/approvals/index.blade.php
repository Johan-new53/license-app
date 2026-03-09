@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
<div class="p-2 bg-light border">

</div>
</div>

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Approval</h2>        
    </div>
     <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        
        <h6>Level : {{ Auth::user()->level }}</h6> 
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


