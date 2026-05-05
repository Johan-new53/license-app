@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">

        <div style="display:flex; justify-content:space-between; align-items:center;">

            <div>
                <h2>Show Approval</h2>
                <h6>Level : {{ Auth::user()->level }}</h6>
            </div>

            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('approvals.index') }}">
                    Back
                </a>
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
                            {{ $finance->invoice_date ? \Carbon\Carbon::parse($finance->invoice_date)->format('d-M-Y') : '-' }}
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Document number(s) :</strong>
                            {{ $finance->doc_no }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
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
            </div>

            <hr>

            {{-- Approval Form --}}
            @if($finance->type == 'digital' && empty($finance->doc_no) && in_array($finance->status, ['requested', 'approved 1', 'rejected 1', 'approved 2', 'rejected 2']))
                <div class="alert alert-warning mb-3">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    <strong>Perhatian!</strong> Document No belum diisi. Silakan lengkapi terlebih dahulu sebelum melakukan approval.
                </div>
                <div class="text-center mb-4">
                    <a href="{{ route('digitals.edit', $finance->id) }}?source=approval_show" class="btn btn-primary">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Dokumen
                    </a>
                </div>
            @else
                @if (Auth::user()->level == 1)
                @if($finance->status=='requested' or $finance->status=='approved 1' or $finance->status=='rejected 1')
                <form action="{{ route('approvals.update', $finance->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="level" value="{{ Auth::user()->level }}">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Due date :</strong>
                                <input type="date" name="due_date" value="{{ $finance->due_date }}" class="form-control" placeholder="">
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Payment Term :</strong>
                                <input type="text" id="payment_term" name="payment_term" value="{{ $finance->payment_term }}" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>PO No :</strong>
                                <input type="text" id="po_no" name="po_no" value="{{ $finance->po_no }}" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Category :</strong>
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
                        <div class="col-md-8">
                            <div class="form-group">
                                <strong>Keterangan * :</strong>
                                <input type="text" 
                                    name="keterangan" 
                                    value="{{ old('keterangan') }}" 
                                    class="form-control @error('keterangan') is-invalid @enderror">
                                @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">    
                        <button name="status" value="approved" type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Approval
                        </button>
                        <button name="status" value="rejected" type="submit" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-xmark"></i> Reject
                        </button>
                    </div>
                </form>
                @endif
            @endif

            @if (Auth::user()->level == 2)
                @if($finance->status=='approved 1' or $finance->status=='approved 2' or $finance->status=='rejected 2')
                <form action="{{ route('approvals.update', $finance->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="level" value="{{ Auth::user()->level }}">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Due date :</strong>
                                <input type="date" name="due_date" value="{{ $finance->due_date }}" class="form-control" placeholder="">
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Payment Term :</strong>
                                <input type="text" id="payment_term" name="payment_term" value="{{ $finance->payment_term }}" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>PO No :</strong>
                                <input type="text" id="po_no" name="po_no" value="{{ $finance->po_no }}" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Category :</strong>
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
                        <div class="col-md-8">
                            <div class="form-group">
                                <strong>Keterangan * :</strong>
                                <input type="text" 
                                    name="keterangan" 
                                    value="{{ old('keterangan') }}" 
                                    class="form-control @error('keterangan') is-invalid @enderror">
                                @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">    
                        <button name="status" value="approved" type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Approval
                        </button>
                        <button name="status" value="rejected" type="submit" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-xmark"></i> Reject
                        </button>
                    </div>
                </form>
                @endif
            @endif
            @endif
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