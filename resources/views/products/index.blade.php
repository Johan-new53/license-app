@extends('layouts.app')


@section('content')


<div class="d-flex justify-content-end">
<div class="p-2 bg-light border">
@can('product-import')
<form class="btn btn-success btn-sm" action="/import-csv" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <button type="submit">Import CSV</button>
</form>
@endcan
</div>
</div>

<div class="row">
<form action="/searchitem/" method="get">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Products</h2>
        @can('product-create')
            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">
                <i class="fa fa-plus"></i> Create New Product
            </a>
        @endcan
        @can('product-export')
            <a class="btn btn-success btn-sm" href="/export-products">
                <i class="fa fa-plus"></i> Export To Excel-csv
            </a>
        @endcan

    </div>
    <div class="col-lg-8 margin-tb d-flex justify-content-between align-items-center mb-3 ">
        <h2 style="margin: 10px;">Item: </h2>
        
        <input type="search" name="searchitem" class="form-control @error('id_unit') is-invalid @enderror">
        <br>
        <button type="submit" class="btn btn-primary" style="margin: 10px;">Search</button>
    </div>
</form>  		
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert"> 
        {{ $message }}
    </div>
@endif

@php
    $i = ($products->currentPage() - 1) * $products->perPage();
@endphp

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Item</th>
            <th>Category</th>
            <th>Description</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $product->item }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->description }}</td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('products.show',$product->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                    @can('product-edit')
                        <a class="btn btn-primary btn-sm" href="{{ route('products.edit',$product->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('product-delete')
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


{{ $products->links('pagination::bootstrap-5') }}
@endsection


