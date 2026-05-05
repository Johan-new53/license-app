@extends('layouts.app')


@section('content')
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
<form action="{{ route('softcopys.index') }}" method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (From)</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Invoice Date (To)</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Payable To</label>
            <select name="id_payable" class="form-control select2">
                <option value="">-- Pilih --</option>
                @foreach ($payabletos as $payableto)
                    <option value="{{ $payableto->id }}">
                        {{ $payableto->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Document No</label>
            <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ request('description') }}" class="form-control">
        </div>
        <div class="col-lg-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- Semua --</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
        </div>

            <div class="row g-1 mb-0">
                <div class="col-10">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>

                <div class="col-2">
                    <a href="{{ route('softcopys.index') }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>
            </div>
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
<hr class="mt-0">
<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th style="width:5%">No</th>
            <th style="width:10%">Invoice Date</th>
            <th style="width:16%">Payable To</th>
            <th style="width:18%">Document No.</th>
            <th style="width:35%">Description</th>
            <th style="width:8%">Status</th>
            <th style="width:8%">Action</th>
        </tr>
        @foreach ($softcopys as $softcopy)
        <tr>
            <td>{{ ++$i }}</td>
            <td style="white-space:nowrap;">
                {{ $softcopy->invoice_date ? $softcopy->invoice_date->format('d-m-Y') : '-' }}
            </td>
            <td style="word-break:break-word;">
                {{ $softcopy->payableto->nama ?? null }}
            </td>
            <td style="word-break:break-word;">
                {{ $softcopy->doc_no }}
            </td>
            <td style="word-break:break-word;">
                {{ $softcopy->description }}
            </td>
            <td style="white-space:nowrap;">
                {{ $softcopy->status }}
            </td>
            <td>
                <form action="{{ route('softcopys.destroy',$softcopy->id) }}" method="POST"
                      style="display:flex; flex-direction:column; gap:5px;">

                    <a class="btn btn-info btn-sm"
                       href="{{ route('softcopys.show',$softcopy->id) }}">
                        <i class="fa-solid fa-list"></i> Show
                    </a>

                    @can('softcopy-edit')
                        @if ($softcopy->status!='paid' && $softcopy->status!='approved 1' && $softcopy->status!='approved 2')
                        <a class="btn btn-primary btn-sm"
                           href="{{ route('softcopys.edit',$softcopy->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        @endif
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('softcopy-delete')
                        @if ($softcopy->status!='paid' && $softcopy->status!='approved 1' && $softcopy->status!='approved 2')
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this item?')">
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


