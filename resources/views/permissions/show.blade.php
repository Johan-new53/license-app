@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Show Permission</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <strong>Name:</strong>
        <p>{{ $permission->name }}</p>
    </div>
    <div class="col-12 mb-3">
        <strong>Guard Name:</strong>
        <p>{{ $permission->guard_name }}</p>
    </div>

    
</div>
@endsection