@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Show Role</h2>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            @if (!empty($rolePermissions))
                @foreach ($rolePermissions as $permission)
                    <span class="badge bg-success">{{ $permission->name }}</span>
                @endforeach
            @else
                <span>No permissions assigned.</span>
            @endif
        </div>
    </div>
</div>
@endsection