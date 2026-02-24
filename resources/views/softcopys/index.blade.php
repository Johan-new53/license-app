@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
<div class="p-2 bg-light border">

</div>
</div>

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Soft Copy</h2>
        @can('softcopy-create')
            <a class="btn btn-success btn-sm" href="{{ route('softcopys.create') }}">
                <i class="fa fa-plus"></i> Create New Soft Copy
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
    $i = ($softcopys->currentPage() - 1) * $softcopys->perPage();
@endphp

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Invoice Date</th>
            <th>Document No.</th>
            <th>Description</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($softcopys as $softcopy)
        <tr>
            <td>{{ ++$i }}</td>            
            <td>{{ $softcopy->invoice_date->format('d-m-Y') }}</td>
            <td>{{ $softcopy->doc_no }}</td>
            <td>{{ $softcopy->description }}</td>
            <td>{{ $softcopy->status }}</td>
            <td>
                <form action="{{ route('softcopys.destroy',$softcopy->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('softcopys.show',$softcopy->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                    @can('softcopy-edit')
                        @if ($softcopy->status<>'paid' and $softcopy->status<>'approve 1' and $softcopy->status<>'approve 2' )
                        <a class="btn btn-primary btn-sm" href="{{ route('softcopys.edit',$softcopy->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        @endif
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('softcopy-delete')
                        @if ($softcopy->status<>'paid' and $softcopy->status<>'approve 1' and $softcopy->status<>'approve 2' )
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                        @endif
                    @endcan
                    

                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
<br>


{{ $softcopys->links('pagination::bootstrap-5') }}
@endsection


