@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center">
        <h2>Change User Password</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('home') }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

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

<form method="POST" action="{{ route('users.update_pwd',  Auth::user()->id) }}">
    @csrf
    @method('PUT')

    <div class="row mt-3">

        <div class="col-12 mb-3">
            <label><strong>Current Password:</strong></label>
            <input type="password" name="current_password" class="form-control" placeholder="Current Password">
        </div>
       
        <div class="col-12 mb-3">
            <label><strong>New Password:</strong></label>
            <input type="password" name="password" class="form-control" placeholder="New Password">
        </div>
        <div class="col-12 mb-3">
            <label><strong>Confirm New Password:</strong></label>
            <input type="password" name="confirm-password" class="form-control" placeholder="Confirm New Password">
        </div>
        
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Submit
            </button>
        </div>
    </div>
</form>

@endsection