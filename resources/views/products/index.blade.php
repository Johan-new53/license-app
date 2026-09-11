@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-end mb-3"> @can('product-import') <form action="/import-csv" method="POST" enctype="multipart/form-data" class="d-flex gap-2"> @csrf <input type="file" name="csv_file" class="form-control form-control-sm"> <button type="submit" class="btn btn-success btn-sm">Import CSV</button> </form> @endcan </div> <div class="d-flex justify-content-between align-items-center mb-3"> <h2>Products</h2>
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

    {{-- 
    @can('product-email-status')
    <a class="btn btn-info btn-sm" href="#">
        Email Status
    </a>
    @endcan
     --}}
     
</div>

</div>

@if ($message = Session::get('success'))

<div class="alert alert-success"> {{ $message }} </div> @endif

@php
$i = ($products->currentPage() - 1) * $products->perPage();
@endphp

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle" style="width:100%;">

    <thead class="table-dark">

        {{-- Header --}}
        <tr>
            <th style="width:5%">No</th>
            <th style="width:15%">Item</th>
            <th style="width:15%">Category</th>
            <th style="width:25%">Description</th>
            <th style="width:15%">Vendor</th>
            <th style="width:10%">PIC</th>
            <th style="width:15%">Action</th>
        </tr>

        {{-- Search --}}
        <tr class="bg-light">
            <th></th>

            <th>
                <input type="text"
                       name="item"
                       form="searchForm"
                       class="form-control form-control-sm"
                       placeholder="Search Item"
                       value="{{ request('item') }}">
            </th>

            <th>
                <input type="text"
                       name="category"
                       form="searchForm"
                       class="form-control form-control-sm"
                       placeholder="Search Category"
                       value="{{ request('category') }}">
            </th>

            <th>
                <input type="text"
                       name="description"
                       form="searchForm"
                       class="form-control form-control-sm"
                       placeholder="Search Description"
                       value="{{ request('description') }}">
            </th>

            <th>
                <input type="text"
                       name="vendor"
                       form="searchForm"
                       class="form-control form-control-sm"
                       placeholder="Search Vendor"
                       value="{{ request('vendor') }}">
            </th>

            <th>
                <input type="text"
                       name="pic"
                       form="searchForm"
                       class="form-control form-control-sm"
                       placeholder="Search PIC"
                       value="{{ request('pic') }}">
            </th>

            <th>
                <div class="d-flex gap-1">
                    <button type="submit"
                            form="searchForm"
                            class="btn btn-primary btn-sm">
                        Search
                    </button>

                    <a href="/searchitem"
                       class="btn btn-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </th>
        </tr>

    </thead>

    <tbody>

    @foreach ($products as $product)

        <tr>
            <td>{{ ++$i }}</td>

            <td style="word-break:break-word;">
                {{ $product->item }}
            </td>

            <td style="word-break:break-word;">
                {{ $product->category }}
            </td>

            <td style="word-break:break-word;">
                {{ $product->description }}
            </td>

            <td style="word-break:break-word;">
                {{ $product->vendor }}
            </td>

            <td style="word-break:break-word;">
                {{ $product->pic }}
            </td>

            <td>

                <form action="{{ route('products.destroy',$product->id) }}"
                      method="POST">

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

{{-- Search form --}}

<form id="searchForm" action="/searchitem" method="GET"> </form>

{{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}

@endsection