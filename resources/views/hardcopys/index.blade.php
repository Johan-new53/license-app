@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
<div class="p-2 bg-light border">

</div>
</div>

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Hard Copy</h2>
        @can('hardcopy-create')
            <a class="btn btn-success btn-sm" href="{{ route('hardcopys.create') }}">
                <i class="fa fa-plus"></i> Create New Hard Copy
            </a>
        @endcan
        

    </div>

    
</form>  		
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert"> 
        {{ $message }}
    </div>
@endif

@php
    $i = ($hardcopys->currentPage() - 1) * $hardcopys->perPage();
@endphp

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Invoice Date</th>
            <th>Document No.</th>
            <th>Description</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($hardcopys as $hardcopy)
        <tr>
            <td>{{ ++$i }}</td>            
            <td>{{ $hardcopy->invoice_date->format('d-m-Y') }}</td>
            <td>{{ $hardcopy->doc_no }}</td>
            <td>{{ $hardcopy->description }}</td>
            <td>
                <form action="{{ route('hardcopys.destroy',$hardcopy->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('hardcopys.show',$hardcopy->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                    @can('hardcopy-edit')
                        <a class="btn btn-primary btn-sm" href="{{ route('hardcopys.edit',$hardcopy->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('hardcopy-delete')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    @endcan
                    

                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
<br>


{{ $hardcopys->links('pagination::bootstrap-5') }}
@endsection


