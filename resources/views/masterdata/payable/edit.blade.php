@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center">
        <h2>Edit Payable</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('payable.index') }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-2">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('payable.update', $payable->id) }}">
    @csrf
    @method('PUT')

    <div class="row mt-3">
        <div class="col-12 mb-3">
            <label><strong>Name:</strong></label>
            <input type="text" name="nama" class="form-control" placeholder="Name" value="{{ old('nama', $payable->nama) }}">
        </div>

        <div class="col-12 mb-3">
            <label><strong>Vendor Account:</strong></label>
            <input type="text" name="vendor_account" class="form-control" placeholder="Vendor Account" value="{{ old('vendor_account', $payable->vendor_account) }}">
        </div>

        <div class="col-12 mb-3">
            <label><strong>TOP (hari):</strong></label>
            <input type="text" name="hari" class="form-control" placeholder="TOP" value="{{ old('hari', $payable->hari) }}">
        </div>

        <div class="col-12 mb-3">
            <label><strong>Valid:</strong></label>
            <select name="valid" class="form-control">
                <option value="1" {{ old('valid', $payable->valid) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('valid', $payable->valid) == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Submit
            </button>
        </div>
    </div>
</form>

@endsection
