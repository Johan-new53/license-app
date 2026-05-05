@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Show Permission</h2>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
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