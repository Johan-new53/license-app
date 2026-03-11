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
    background:#ffc107;
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
            <h2>Show Approval</h2>
            <h6>Level : {{ Auth::user()->level }}</h6>   
        
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('approvals.index') }}">Back</a>
        </div>
    </div>
</div>



<div class="container">
<div class="row">
<div class="col-md-6">

    

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Type :</strong>
            {{ $finance->type }}
        </div>
    </div>

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
            {{ $finance->nama_rek_tujuan }}
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
            <strong>Currency :</strong>
            {{ $finance->nama_currency }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>File Softcopy :</strong><br>

            @if($finance->input_file)
                <a href="{{ asset('storage/' . $finance->input_file) }}" 
                target="_blank" 
                class="btn btn-success btn-sm">
                    Lihat / Download File
                </a>
            @else
                <span class="text-danger">File tidak tersedia</span>
            @endif
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
                ⏳    
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
</div>

@if (Auth::user()->level ==1)
@if($finance->status=='requested' or $finance->status=='approved 1' or $finance->status=='rejected 1')
<form action="{{ route('approvals.update', $finance->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="level" value="{{ Auth::user()->level }}">
    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Keterangan * :</strong>
        <input type="text" 
               name="keterangan" 
               value="{{ old('keterangan') }}" 
               class="form-control @error('keterangan') is-invalid @enderror">

        @error('keterangan')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6 text-center">    
        <button name="status" value="approved" 
            type="submit" 
            class="btn btn-primary btn-sm mb-3 mt-2"
            >
            <i class="fa-solid fa-check"></i> Approval
        </button>
        <button name="status" value="rejected" 
            type="submit" 
            class="btn btn-danger btn-sm mb-3 mt-2"
            >
            <i class="fa-solid fa-xmark"></i> Reject
        </button>
    </div>
</form>
@endif
@endif

@if (Auth::user()->level ==2)
@if($finance->status=='approved 1' or $finance->status=='approved 2' or $finance->status=='rejected 2')
<form action="{{ route('approvals.update', $finance->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="level" value="{{ Auth::user()->level }}">
    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Keterangan * :</strong>
        <input type="text" 
               name="keterangan" 
               value="{{ old('keterangan') }}" 
               class="form-control @error('keterangan') is-invalid @enderror">

        @error('keterangan')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6 text-center">    
        <button name="status" value="approved" 
            type="submit" 
            class="btn btn-primary btn-sm mb-3 mt-2"
            >
            <i class="fa-solid fa-check"></i> Approval
        </button>
        <button name="status" value="rejected" 
            type="submit" 
            class="btn btn-danger btn-sm mb-3 mt-2"
            >
            <i class="fa-solid fa-xmark"></i> Reject
        </button>
    </div>
</form>
@endif
@endif


@endsection