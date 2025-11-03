@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center">
        <h2>Edit Permission</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}">
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

<form method="POST" action="{{ route('permissions.update', $permission->id) }}">
    @csrf
    @method('PUT')

    <div class="row mt-3">
        <div class="col-12 mb-3">
            <label><strong>Name:</strong></label>
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name', $permission->name) }}">
        </div>
       

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Submit
            </button>
        </div>
    </div>
</form>

@endsection