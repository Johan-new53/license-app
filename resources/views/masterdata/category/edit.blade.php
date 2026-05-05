@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Edit Category</h2>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('category.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
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

<form method="POST" action="{{ route('category.update', $category->id) }}">
    @csrf
    @method('PUT')

    <div class="row mt-3">
        <div class="col-12 mb-3">
            <label><strong>Name:</strong></label>
            <input type="text" name="nama" class="form-control" placeholder="Name" value="{{ old('nama', $category->nama) }}">
        </div>

        <div class="col-12 mb-3">
            <label><strong>Valid:</strong></label>
            <select name="valid" class="form-control">
                <option value="1" {{ old('valid', $category->valid) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('valid', $category->valid) == 0 ? 'selected' : '' }}>No</option>
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
