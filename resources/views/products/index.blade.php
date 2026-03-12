@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-end mb-3">
    @can('product-import')
    <form action="/import-csv" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="csv_file" class="form-control form-control-sm">
        <button type="submit" class="btn btn-success btn-sm">Import CSV</button>
    </form>
    @endcan
</div>


<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Products</h2>

    <div class="d-flex gap-2">

        @can('product-create')
        <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">
            <i class="fa fa-plus"></i> Create
        </a>
        @endcan

        @can('product-export')
        <a class="btn btn-primary btn-sm" href="/export-products">
            Export CSV
        </a>
        @endcan

        @can('product-email-status')
        <a class="btn btn-info btn-sm" href="#">
            Email Status
        </a>
        @endcan

    </div>
</div>


<form action="/searchitem" method="GET" class="mb-3">

<div class="row g-2">

    <div class="col-md-2">
        <input type="text" name="item" class="form-control"
        placeholder="Search Item"
        value="{{ request('item') }}">
    </div>

    <div class="col-md-2">
        <input type="text" name="category" class="form-control"
        placeholder="Search Category"
        value="{{ request('category') }}">
    </div>

    <div class="col-md-3">
        <input type="text" name="description" class="form-control"
        placeholder="Search Description"
        value="{{ request('description') }}">
    </div>

    <div class="col-md-2">
        <input type="text" name="pic" class="form-control"
        placeholder="Search PIC"
        value="{{ request('pic') }}">
    </div>

    <div class="col-md-1 d-grid">
        <button class="btn btn-primary">Search</button>
    </div>

    <div class="col-md-1 d-grid">
        <a href="/searchitem" class="btn btn-secondary">Reset</a>
    </div>

</div>

</form>


@if ($message = Session::get('success'))
<div class="alert alert-success">
{{ $message }}
</div>
@endif


@php
$i = ($products->currentPage() - 1) * $products->perPage();
@endphp


<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-dark">
<tr>
<th>No</th>
<th>Item</th>
<th>Category</th>
<th>Description</th>
<th>PIC</th>
<th width="250">Action</th>
</tr>
</thead>

<tbody>
@foreach ($products as $product)

<tr>
<td>{{ ++$i }}</td>
<td>{{ $product->item }}</td>
<td>{{ $product->category }}</td>
<td>{{ $product->description }}</td>
<td>{{ $product->pic }}</td>

<td>

<form action="{{ route('products.destroy',$product->id) }}" method="POST">

<a class="btn btn-info btn-sm"
href="{{ route('products.show',$product->id) }}">
Show
</a>

@can('product-edit')
<a class="btn btn-primary btn-sm"
href="{{ route('products.edit',$product->id) }}">
Edit
</a>
@endcan

@csrf
@method('DELETE')

@can('product-delete')
<button type="submit"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this item?')">
Delete
</button>
@endcan

</form>

</td>
</tr>

@endforeach
</tbody>

</table>

</div>


{{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}

@endsection