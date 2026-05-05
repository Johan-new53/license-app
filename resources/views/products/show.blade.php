
@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Show Product</h2>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>


<div class="container">
<div class="row">
<div class="col-md-6">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Item:</strong>
            {{ $product->item }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Category:</strong>
            {{ $product->category }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Description:</strong>
            {{ $product->description }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Qty:</strong>
            {{ $product->qty }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Used:</strong>
            {{ $product->used }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Remaining:</strong>
            {{ $product->remaining }}
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Start date:</strong>
            {{ $product->start_date ? \Carbon\Carbon::parse($product->start_date)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>End date:</strong>
            {{ $product->end_date ? \Carbon\Carbon::parse($product->end_date)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Last bidding:</strong>
            {{ $product->last_bidding ? \Carbon\Carbon::parse($product->last_bidding)->format('d-M-Y') : '-' }}            
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Next bidding:</strong>            
            {{ $product->next_bidding ? \Carbon\Carbon::parse($product->next_bidding)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Renewal date:</strong>            
            {{ $product->renewal_date ? \Carbon\Carbon::parse($product->renewal_date)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Tgl email 1:</strong>            
            {{ $product->tgl_email1 ? \Carbon\Carbon::parse($product->tgl_email1)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Tgl email 2:</strong>
            {{ $product->tgl_email2 ? \Carbon\Carbon::parse($product->tgl_email2)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Tgl email 3:</strong>
            {{ $product->tgl_email3 ? \Carbon\Carbon::parse($product->tgl_email3)->format('d-M-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Request date:</strong>  
             @if ($product->request_date == '0000-00-00')         
                
            @else
                {{ $product->request_date ? \Carbon\Carbon::parse($product->request_date)->format('d-M-Y') : '-' }}
            @endif
        </div>
    </div>


</div>

    <div class="col-md-6">
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Vendor:</strong>
                    {{ $product->vendor }}
                </div>
            </div>
        
        
             <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mata uang:</strong>
                    {{ $product->mata_uang }}
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Amount excl vat:</strong>
                    {{ number_format($product->amount_excl_vat,0,'.',',') }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Pr:</strong>
                    {{ $product->pr }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Po:</strong>
                    {{ $product->po }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Pic:</strong>
                    {{ $product->pic }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mail Pic:</strong>
                    {{ $product->mail_pic }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Hp Pic:</strong>
                    {{ $product->hp_pic }}
                </div>
            </div>
        
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    {{ $product->status }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Date update status:</strong>    
                    @if ($product->date_update_status == '0000-00-00') 
                    @else
                        {{ $product->date_update_status ? \Carbon\Carbon::parse($product->date_update_status)->format('d-M-Y') : '-' }}
                    @endif
                
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>No tiket:</strong>
                    {{ $product->no_tiket }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Remark:</strong>
                    {{ $product->remark }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nama Admin:</strong>
                    {{ $product->nama_admin }}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email Admin:</strong>
                    {{ $product->email_admin }}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Hp Admin:</strong>
                    {{ $product->hp_admin }}
                </div>
            </div>

    </div>
</div>
@endsection