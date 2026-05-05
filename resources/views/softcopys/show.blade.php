



@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Show Soft Copy</h2>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('softcopys.index') }}">Back</a>
            </div>
        </div>
    </div>
</div>



<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Payment Term :</strong>
                            {{ $finance->payment_term }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>PO Number :</strong>
                            {{ $finance->po_no }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>PO Category :</strong>
                            {{ $finance->nama_category }}
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
                </div>

                <div class="col-md-6">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Invoice Date:</strong>
                            {{ $finance->invoice_date ? \Carbon\Carbon::parse($finance->invoice_date)->format('d-M-Y') : '-' }}
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
                            <strong>Total Amount :</strong>
                            {{ number_format($finance->total_amount,0,'.',',') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="mb-3"><i class="fa-solid fa-list-check me-1"></i> Approval Flow</h4>
            <div style="max-height: 60vh; overflow-y: auto; padding-right: 4px;">
                @foreach($histories->sortByDesc('created_at') as $key => $row)
                @php
                    $isLast     = $loop->last;
                    $isApproved = in_array($row->status, ['approved 1','approved 2','paid']);
                    $isRejected = in_array($row->status, ['rejected 1','rejected 2']);
                    $dotColor   = $isApproved ? '#28a745' : ($isRejected ? '#dc3545' : '#ffc107');
                    $textColor  = $isApproved ? '#28a745' : ($isRejected ? '#dc3545' : '#856404');
                    $bgColor    = $isApproved ? '#d4edda'  : ($isRejected ? '#f8d7da'  : '#fff3cd');
                    $icon       = $isApproved ? 'fa-check'  : ($isRejected ? 'fa-xmark'  : 'fa-clock');
                @endphp
                <div style="display:flex; gap:12px; position:relative;">
                    <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
                        <div style="width:32px; height:32px; border-radius:50%; background:{{ $dotColor }}; display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; box-shadow:0 2px 6px rgba(0,0,0,.15); flex-shrink:0;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        @if(!$isLast)
                        <div style="flex:1; width:2px; background:#dee2e6; min-height:18px; margin:2px 0;"></div>
                        @endif
                    </div>
                    <div style="background:{{ $bgColor }}; border-radius:8px; padding:8px 12px; flex:1; font-size:12px; line-height:1.5; margin-bottom:{{ $isLast ? '0' : '8' }}px;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <span style="font-weight:700; text-transform:uppercase; color:{{ $textColor }}; font-size:11px;">{{ $row->status }}</span>
                            <span style="color:#6c757d; font-size:10px; white-space:nowrap; margin-left:8px;">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/y H:i') }}</span>
                        </div>
                        <div style="font-weight:600; color:#212529;">{{ $row->name }}</div>
                        @if($row->keterangan)
                        <div style="color:#6c757d; font-style:italic; border-top:1px solid rgba(0,0,0,.08); margin-top:4px; padding-top:4px;">{{ $row->keterangan }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
