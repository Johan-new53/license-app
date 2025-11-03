@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2>Permission Management</h2>
        @can('permission-create')
            <a class="btn btn-success btn-sm" href="{{ route('permissions.create') }}">
                <i class="fa fa-plus"></i> Create New Permission
            </a>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="100px">No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permissions as $key => $permission)
        <tr>
            
            <td>{{ ++$i }}</td>
            <td>{{ $permission->name }}</td>
            <td>
                <a class="btn btn-info btn-sm" href="{{ route('permissions.show', $permission->id) }}">
                    <i class="fa-solid fa-list"></i> Show
                </a>
                @can('permission-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('permissions.edit', $permission->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
                @can('permission-delete')
                    <form method="POST" action="{{ route('permissions.destroy', $permission->id) }}" onsubmit="return confirmDelete();" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $permissions->links('pagination::bootstrap-5') !!}

@endsection

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}
</script>