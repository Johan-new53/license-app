@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Hard Copy</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="{{ route('hardcopys.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


<form action="{{ route('hardcopys.update', $finance->id) }}" method="POST">
    @csrf
    @method('PUT')

<div class="container mt-4">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data1-tab" data-bs-toggle="tab" data-bs-target="#data1"
                type="button" role="tab" aria-controls="data1" aria-selected="true">Requesting</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="data2-tab" data-bs-toggle="tab" data-bs-target="#data2"
                type="button" role="tab" aria-controls="data2" aria-selected="false">Rekening Tujuan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#data3"
                type="button" role="tab" aria-controls="data3" aria-selected="false">Document Number</button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#data4"
                type="button" role="tab" aria-controls="data4" aria-selected="false">Amount</button>
        </li>

        
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="data1" role="tabpanel" aria-labelledby="data1-tab">
            
            <div class="col-xs-4 col-sm-4 col-md-4">
            <strong>Requesting Department * :</strong>
            <select name="id_dept" class="form-control select2" required>
                <option value="">-- Pilih --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}"
                        {{ old('id_dept', $finance->id_dept) == $dept->id ? 'selected' : '' }}>
                        {{ $dept->nama }}
                    </option>
                @endforeach
            </select>
            </div>
            <br>
           
            <div class="col-xs-4 col-sm-4 col-md-4">   
                    <strong>Hospital unit dan Rekening sumber * :</strong>
                    <select name="id_rek_sumber" class="form-control select2" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($hu_rek_sumbers as $hu_rek_sumber)
                            <option value="{{ $hu_rek_sumber->id }}"                                
                                {{ old('id_rek_sumber', $finance->id_rek_sumber) == $hu_rek_sumber->id ? 'selected' : '' }}>
                                {{ $hu_rek_sumber->nama }}
                            </option>
                        @endforeach
                    </select>
            </div>
            <br>

            <div class="col-xs-4 col-sm-4 col-md-4">   
                    <strong>Payable To * :</strong>
                    <select name="id_payable_h" class="form-control select2" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($payableto_hs as $payableto_h)
                            <option value="{{ $payableto_h->id }}"
                                {{ old('id_payable_h', $finance->id_payable_h) == $payableto_h->id ? 'selected' : '' }}>
                                {{ $payableto_h->nama }}
                            </option>
                        @endforeach
                    </select>
            </div>            
            <br>
        </div>
        <div class="tab-pane fade p-3" id="data2" role="tabpanel" aria-labelledby="data2-tab">
         
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Nama Rekening Tujuan * :</strong>
                    <input type="text" name="nama_rekening_tujuan" value="{{ $finance->nama_rekening_tujuan }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>
            <div class="col-xs-4 col-sm-4 col-md-4">   
                        <strong>Bank Tujuan * :</strong>
                        <select name="id_bank" class="form-control select2" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ old('id_bank', $finance->id_bank) == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->nama }}
                                </option>
                            @endforeach
                        </select>
                </div>          
            <br>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>No Rekening Tujuan * :</strong>
                    <input type="text" name="no_rek_tujuan" value="{{ $finance->no_rek_tujuan }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2 ">
                <div class="form-group">
                    <strong>Invoice date * :</strong>
                    <input type="date" name="invoice_date" value="{{ $finance->invoice_date }}"  class="form-control" placeholder="">
                </div>
            </div>
        </div>
        <div class="tab-pane fade p-3" id="data3" role="tabpanel" aria-labelledby="data3-tab">   

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Document Number(s) * :</strong>   <br>    
                    <strong>Diperbolehkan lebih dari 1 dokumen contoh (12345678,456789123)</strong>   <br>             
                    <input type="text" name="doc_no" value="{{ $finance->doc_no }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description * :</strong>                    
                    <input type="text" name="description" value="{{ $finance->description }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>
            <div class="col-xs-4 col-sm-4 col-md-4">   
                        <strong>Currency * :</strong>
                        <select name="id_currency" class="form-control select2" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($currencys as $currency)
                                <option value="{{ $currency->id }}"
                                    {{ old('id_currency', $finance->id_currency) == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->nama }}
                                </option>
                            @endforeach
                        </select>
                </div>          
            <br>
          
            <br>
            
        </div>

         <div class="tab-pane fade p-3" id="data4" role="tabpanel" aria-labelledby="data4-tab">   

            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Dpp * :</strong>   <br>                        
                    <input type="number" id="dpp" name="dpp" value="{{ $finance->dpp }}" class="form-control" placeholder="">
                </div>
            </div>
            <br> 

             <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Ppn% (0,1,11) * :</strong>   <br>                        
                    <input type="number" id="ppn_persen" name="persen_ppn" value="{{ $finance->persen_ppn }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>

             <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Nilai Ppn * :</strong>   <br>                        
                    <input type="number" id="nilai_ppn" name="nilai_ppn" value="{{ $finance->nilai_ppn }}" class="form-control" placeholder="" readonly>
                </div>
            </div> 
            <br>

            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>PPH * :</strong>   <br>                        
                    <input type="number" id="pph" name="pph" value="{{ $finance->pph }}" class="form-control" placeholder="">
                </div>
            </div>
            <br>

            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Total Amount * :</strong>   <br>                        
                    <input type="number" id="total_amount" name="total_amount" value="{{ $finance->total_amount }}" class="form-control" placeholder="" readonly>
                </div>
            </div>
            <br>
           
            
        </div>

        

<div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
</div>     
       

</div>
</form>
 
<script>

function hitungTotal() {
    let dpp = parseFloat(document.getElementById('dpp').value) || 0;
    let ppnPersen = parseFloat(document.getElementById('ppn_persen').value) || 0;
    let pph = parseFloat(document.getElementById('pph').value) || 0;

    let ppnNilai = ppnPersen / 100 * dpp;
    let total = dpp + ppnNilai + pph;

    
    document.getElementsByName('nilai_ppn')[0].value = ppnNilai;
    document.getElementsByName('total_amount')[0].value = total;
}

// trigger saat input berubah
document.getElementById('dpp').addEventListener('input', hitungTotal);
document.getElementById('ppn_persen').addEventListener('input', hitungTotal);
document.getElementById('pph').addEventListener('input', hitungTotal);
</script>

@endsection

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Pilih Department --",
            allowClear: true
        });
    });
</script>

