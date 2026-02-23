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

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">
                                Proses Import
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
