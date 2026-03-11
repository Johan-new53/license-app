<style>

.approval-wrapper{
    display:flex;
    align-items:flex-start;
    margin-top:20px;
}

.step{
    text-align:center;
    position:relative;
    flex:1;
}

.step:after{
    content:'';
    position:absolute;
    top:20px;
    right:-50%;
    width:100%;
    height:3px;
    background:#ddd;
    z-index:-1;
}

.step:last-child:after{
    display:none;
}

.step-circle{
    width:40px;
    height:40px;
    border-radius:50%;
    color:white;
    line-height:40px;
    margin:auto;
    font-size:18px;
    font-weight:bold;
}

.approved{
    background:#28a745;
}

.rejected{
    background:#dc3545;
}

.requested{
    background:#28a745;
}

.pending{
    background:#ffc107;
}

.step-content{
    margin-top:10px;
    font-size:13px;
}

</style>



@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Show Hard Copy</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('hardcopys.index') }}">Back</a>
        </div>
    </div>
</div>



<div class="container">
<div class="row">

<!-- KOLOM KIRI -->
<div class="col-md-6">


    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Requesting Departemen :</strong>
            {{ $finance->nama_dept }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Hospital unit dan Rekening sumber  :</strong>
            {{ $finance->nama_rek_sumber }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Payable To  :</strong>
            {{ $finance->nama_payable }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nama Rekening Tujuan:</strong>
            {{ $finance->nama_rekening_tujuan }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Bank Tujuan :</strong>
            {{ $finance->nama_bank }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>No Rekening Tujuan:</strong>
            {{ $finance->no_rek_tujuan }}
        </div>
    </div>

    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Invoice Date:</strong>
            {{ \Carbon\Carbon::parse($finance->invoice_date)->format('d-M-Y') }}
        </div>
    </div>
    
     <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Document number(s) :</strong>
            {{ $finance->doc_no }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Description :</strong>
            {{ $finance->description }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Activity Code :</strong>
            {{ $finance->activity_code }}
        </div>
    </div>



    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Currency :</strong>
            {{ $finance->nama_currency }}
        </div>
    </div>

            
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Dpp :</strong>
            {{ number_format($finance->dpp,0,'.',',') }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Ppn :</strong>
            {{ $finance->nama_ppn }}
        </div>
    </div>

     <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Ppn % :</strong>
            {{ number_format($finance->persen_ppn,0,'.',',') }}
        </div>
    </div>

     <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nilai Ppn :</strong>
            {{ number_format($finance->nilai_ppn,0,'.',',') }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Pph :</strong>
            {{ number_format($finance->pph,0,'.',',') }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Total Amount :</strong>
            {{ number_format($finance->total_amount,0,'.',',') }}
        </div>
    </div>


</div>

<div class="col-md-6">

<h4>Approval Flow</h4>

<div class="approval-wrapper">

@foreach($histories as $key => $row)

<div class="step">

    <div class="step-circle
        @if($row->status == 'approved 1' or $row->status == 'approved 2') approved
        @elseif($row->status == 'rejected 1' or $row->status == 'rejected 2') rejected
        @else requested
        @endif
    ">

        @if($row->status == 'approved 1' or $row->status == 'approved 2')
            ✔
        @elseif($row->status == 'rejected 1' or $row->status == 'rejected 2')
            ✖
        @elseif($row->status == 'requested' )
            ✔    
        @else
            ⏳
        @endif

    </div>

    <div class="step-content">

        <strong>{{ $row->status }}</strong><br>

        <small>{{ $row->keterangan }}</small><br>

        <small>
            {{ $row->name }}
        </small><br>

        <small>
            {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}
        </small>

    </div>

</div>

@endforeach

</div>
</div>



@endsection