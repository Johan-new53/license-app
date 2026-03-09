@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                     <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3"> 
                        <h6>Name : {{ Auth::user()->name }}</h6>                        
                        
                    </div>

                     <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3"> 
                                              
                        <h6>Level : {{ Auth::user()->level }}</h6> 
                    </div>
                    <h6>Role :
                        @foreach(Auth::user()->getRoleNames() as $role)
                            <span class="badge bg-success">{{ $role }}</span>
                        @endforeach
                    </h6>
                 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
