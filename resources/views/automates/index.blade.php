@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
<div class="p-2 bg-light border">

</div>
</div>

<div class="row">
<form action="" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Automate</h2>
        @can('automate-create')
            <a class="btn btn-success btn-sm" href="{{ route('automates.create') }}">
                <i class="fa fa-plus"></i> Create New Automate
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
    $i = ($automates->currentPage() - 1) * $automates->perPage();
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
        @foreach ($automates as $automate)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $automate->invoice_date->format('d-m-Y') }}</td>
            <td>{{ $automate->doc_no }}</td>
            <td>{{ $automate->description }}</td>
            <td>{{ $automate->status }}</td>
            <td>
                <form action="{{ route('automates.destroy',$automate->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('automates.show',$automate->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                    @can('automate-edit')
                        @if ($automate->status<>'paid' and $automate->status<>'approve 1' and $automate->status<>'approve 2' )
                        <a class="btn btn-primary btn-sm" href="{{ route('automates.edit',$automate->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        @endif
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('automate-delete')
                        @if ($automate->status<>'paid' and $automate->status<>'approve 1' and $automate->status<>'approve 2' )
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


{{ $automates->links('pagination::bootstrap-5') }}
@endsection


