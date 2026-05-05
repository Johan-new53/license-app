@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1/dist/cleave.min.js"></script>



@php
    $source = request('source');
    $backUrl = route('digitals.index');
    if ($source == 'approval_index') {
        $backUrl = route('approvals.index');
    } elseif ($source == 'approval_show') {
        $backUrl = route('approvals.show', $finance->id);
    }
@endphp

<form action="{{ route('digitals.update', $finance->id) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <input type="hidden" name="source" value="{{ $source }}">

    <div class="row mb-3">
        <div class="col-lg-12">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h2>Edit Digital</h2>
                </div>
                <div>
                    <a class="btn btn-primary btn-sm" href="{{ $backUrl }}">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

   <div class="col-md-8">
    <div class="d-flex align-items-center">
        <strong class="me-2">Komen perbaikan rejected atau alasan edit:</strong>
        <input type="text" name="alasan" class="form-control" placeholder="Masukkan komen perbaikan rejected atau alasan edit" required>
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




<div class="container mt-4">
    @if(Auth::user()->level != 0)
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data1-tab" data-bs-toggle="tab" data-bs-target="#data1"
                type="button" role="tab" aria-controls="data1" aria-selected="true">Document Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="data2-tab" data-bs-toggle="tab" data-bs-target="#data2"
                type="button" role="tab" aria-controls="data2" aria-selected="true">Requesting</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="data3-tab" data-bs-toggle="tab" data-bs-target="#data3"
                type="button" role="tab" aria-controls="data3" aria-selected="false">Rekening Tujuan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="data4-tab" data-bs-toggle="tab" data-bs-target="#data4"
                type="button" role="tab" aria-controls="data4" aria-selected="false">Document Number</button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="data5-tab" data-bs-toggle="tab" data-bs-target="#data5"
                type="button" role="tab" aria-controls="data5" aria-selected="false">Amount</button>
        </li>

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="data1" role="tabpanel" aria-labelledby="data1-tab">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Payment Term * :</strong>
                    <input type="text" name="payment_term" class="form-control" placeholder="" value="{{ $finance->payment_term }}" required>
                </div>
            </div>
            <br/>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>PO Number :</strong>
                    <input type="text" name="po_no" class="form-control" placeholder="" value="{{ $finance->po_no }}">
                </div>
            </div>
            <br/>
            <div class="col-xs-4 col-sm-4 col-md-4">
                <strong>PO Category * :</strong>
                <select name="id_category" class="form-control select2" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($categorys as $category)
                        <option value="{{ $category->id }}"
                            {{ old('id_category', $finance->id_category) == $category->id ? 'selected' : '' }}>
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="tab-pane fade p-3" id="data2" role="tabpanel" aria-labelledby="data2-tab">

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
                    <select name="id_payable" class="form-control select2" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($payabletos as $payableto)
                            <option value="{{ $payableto->id }}"
                                {{ old('id_payable', $finance->id_payable) == $payableto->id ? 'selected' : '' }}>
                                {{ $payableto->nama }}
                            </option>
                        @endforeach
                    </select>
            </div>
            <br>
        </div>
        <div class="tab-pane fade p-3" id="data3" role="tabpanel" aria-labelledby="data3-tab">

            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Nama Rekening Tujuan * :</strong>
                    <input type="text" name="nama_rekening_tujuan" value="{{ $finance->nama_rekening_tujuan }}" class="form-control" placeholder="" required>
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
                    <input type="text" name="no_rek_tujuan" value="{{ $finance->no_rek_tujuan }}" class="form-control" placeholder="" required>
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2 ">
                <div class="form-group">
                    <strong>Invoice date * :</strong>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $finance->invoice_date ? date('Y-m-d', strtotime($finance->invoice_date)) : '') }}"  class="form-control" placeholder="" required>
                </div>
            </div>
        </div>
        <div class="tab-pane fade p-3" id="data4" role="tabpanel" aria-labelledby="data4-tab">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Document Number(s) * :</strong><br>
                    <strong>Diperbolehkan lebih dari 1 dokumen contoh (12345678;456789123)</strong><br>

                    <input id="doc_no" type="text" name="doc_no" value="{{ $finance->doc_no }}" class="form-control" placeholder="" required>
                    <div id="docNoResult" class="mt-2"></div>
                </div>
            </div>
            <br>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description * :</strong>
                    <input type="text" name="description" value="{{ $finance->description }}" class="form-control" placeholder="" required>
                </div>
            </div>
            <br>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Activity Code * :</strong>
                    <input type="text" name="activity_code" value="{{ $finance->activity_code }}" class="form-control" placeholder="" required>
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
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Journal Number * :</strong>
                    <input type="text" name="journal_no" value="{{ $finance->journal_no }}" class="form-control" placeholder="" required>
                </div>
            </div>
            <br>

        </div>

         <div class="tab-pane fade p-3" id="data5" role="tabpanel" aria-labelledby="data5-tab">

            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Dpp * :</strong>   <br>
                    <input type="text" id="dpp" name="dpp" value="{{ $finance->dpp }}" class="form-control" placeholder="" required>
                </div>
            </div>

            <div class="col-xs-3 col-sm-3 col-md-3">
            <strong>Ppn (Pilih 0,1,11,Other) * :</strong>
            <select name="id_ppn" id="id_ppn" class="form-control select2" required>
                <option value="">-- Pilih --</option>
                @foreach ($ppns as $ppn)
                    <option value="{{ $ppn->id }}"
                        {{ old('id_ppn', $finance->id_ppn) == $ppn->id ? 'selected' : '' }}
                        data-ppn="{{ $ppn->ppn }}"
                        data-flag="{{ $ppn->flag_ubah }}">
                        {{ $ppn->nama }}
                    </option>
                @endforeach
            </select>
            </div>


             <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Ppn% (0,1,11) * :</strong>   <br>
                    <input type="number" id="ppn_persen" name="persen_ppn" value="{{ $finance->persen_ppn }}" class="form-control" placeholder="" readonly>
                </div>
            </div>


             <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Nilai Ppn * :</strong>   <br>
                    <input type="text" id="nilai_ppn" name="nilai_ppn" value="{{ $finance->nilai_ppn }}" class="form-control" placeholder="" readonly>
                </div>
            </div>


            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>PPH * :</strong>   <br>
                    <input type="text" id="pph" name="pph" value="{{ $finance->pph }}" class="form-control" placeholder="" required>
                </div>
            </div>


            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>Total Amount * :</strong>   <br>
                    <input type="text" id="total_amount" name="total_amount" value="{{ $finance->total_amount }}" class="form-control" placeholder="" readonly>
                </div>
            </div>



        </div>
    </div>

    @else
        <div class="p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <strong>Journal Number * :</strong>
                        <input type="text" name="journal_no" value="{{ $finance->journal_no }}" class="form-control" placeholder="Masukkan Journal Number" required>
                    </div>
                </div>
            </div>
        </div>
        @endif




<div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button id="submit" type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
</div>


</div>
</form>

<script>
  window.DOCNO_CHECK = {
    url: "{{ route('checkDocNo') }}",
    type: "all",
    ignore_id: {{ $finance->id }},
    filter_field: "id_dept", // bersifat optional contoh: "id_dept" (sesuaikan dengan nama field filter di form)
    filter_label: "Departemen", // label untuk pesan error (sesuaikan dengan nama field filter di form)
  };
</script>
<script src="{{ asset('js/docno-check.js') }}"></script>

<script>
    let cleaveDpp = new Cleave('#dpp', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    let cleavePph = new Cleave('#pph', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    let cleavePpn = new Cleave('#nilai_ppn', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    let cleaveTotal = new Cleave('#total_amount', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    function hitungTotal() {
        let dpp = parseFloat(cleaveDpp.getRawValue()) || 0;
        let pph = parseFloat(cleavePph.getRawValue()) || 0;
        let ppnPersen = parseFloat(document.getElementById('ppn_persen').value) || 0;

        let ppnNilai = 0;

        if ($('#nilai_ppn').prop('readonly')) {
            ppnNilai = (ppnPersen / 100) * dpp;
            cleavePpn.setRawValue(ppnNilai);
        } else {
            ppnNilai = parseFloat(cleavePpn.getRawValue()) || 0;
        }

        let total = dpp + ppnNilai - pph;
        cleaveTotal.setRawValue(total);
    }

    // trigger saat input berubah


    $('#dpp, #pph, #nilai_ppn, #ppn_persen').on('input', function () {
        hitungTotal();
    });

    $('#id_ppn').on('change', function () {
        hitungTotal();
    });

    document.getElementById('id_ppn').addEventListener('change', function() {

        let selected = this.options[this.selectedIndex];

        let persen = parseFloat(selected.getAttribute('data-ppn')) || 0;
        let flag = parseInt(selected.getAttribute('data-flag')) || 0;

        let ppnPersenInput = document.getElementById('ppn_persen');
        let nilaiPpnInput = document.getElementById('nilai_ppn');

        // set persen
        ppnPersenInput.value = persen;

        // atur readonly dulu (PENTING urutan ini)
        if (flag === 0) {
            nilaiPpnInput.readOnly = true;
            nilaiPpnInput.value = 0; // reset dulu supaya bersih
        } else {
            nilaiPpnInput.readOnly = false;
            nilaiPpnInput.value = 0; // reset juga supaya tidak bawa nilai lama
        }

        // hitung ulang setelah semua set
        hitungTotal();
    });

    document.getElementById('nilai_ppn').addEventListener('input', hitungTotal);

    $('form').on('submit', function (e) {
        // Cek validasi manual untuk menangani Tabs
        let requiredFields = $(this).find('[required]');
        let emptyFields = [];
        let firstEmptyField = null;

        requiredFields.each(function() {
            if ($(this).val() === '' || $(this).val() === null) {
                // Ambil label dari elemen <strong> sebelumnya atau atribut placeholder/name
                let label = $(this).closest('.form-group, .col-xs-4, .col-xs-6, .col-xs-3, .col-xs-12').find('strong').first().text().replace(' * :', '').replace('* :', '').trim();
                if (!label) label = $(this).attr('placeholder') || $(this).attr('name');
                emptyFields.push(label);
                
                if (!firstEmptyField) firstEmptyField = $(this);
            }
        });

        if (emptyFields.length > 0) {
            e.preventDefault(); // Batalkan submit
            
            alert("Harap isi field berikut:\n- " + emptyFields.join("\n- "));

            // Jika field ada di dalam tab, pindah ke tab tersebut
            let tabPane = firstEmptyField.closest('.tab-pane');
            if (tabPane.length > 0) {
                let tabId = tabPane.attr('id');
                let tabButton = $('button[data-bs-target="#' + tabId + '"]');
                if (tabButton.length > 0) {
                    tabButton.tab('show');
                }
            }

            // Fokus ke field
            setTimeout(function() {
                firstEmptyField.focus();
                if (firstEmptyField.hasClass('select2-hidden-accessible')) {
                    firstEmptyField.select2('open');
                }
            }, 300);
            
            return false;
        }

        // Jika valid, jalankan proses submit (cleave raw value)
        $('#dpp').val(cleaveDpp.getRawValue());
        $('#pph').val(cleavePph.getRawValue());
        $('#nilai_ppn').val(cleavePpn.getRawValue());
        $('#total_amount').val(cleaveTotal.getRawValue());
    });

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

