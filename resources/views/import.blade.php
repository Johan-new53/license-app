@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    Import Data
                </div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File EXCEL</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                            <small class="text-muted">
                                Format: Excel
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary">
                                <i class="fa fa-upload"></i> Proses Import
                            </button>
                            <a href="{{ route('import.template') }}" class="btn btn-outline-success">
                                <i class="fa fa-file-excel"></i> Download Template
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
