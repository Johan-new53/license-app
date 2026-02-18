@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')


<div class="container mt-4">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="item-tab" data-bs-toggle="tab" data-bs-target="#item"
                type="button" role="tab" aria-controls="item" aria-selected="true">Item</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="quantity-tab" data-bs-toggle="tab" data-bs-target="#quantity"
                type="button" role="tab" aria-controls="quantity" aria-selected="false">Quantity</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#renewal"
                type="button" role="tab" aria-controls="renewal" aria-selected="false">Renewal</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#email"
                type="button" role="tab" aria-controls="email" aria-selected="false">Email</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#vendor"
                type="button" role="tab" aria-controls="vendor" aria-selected="false">Vendor</button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#pic"
                type="button" role="tab" aria-controls="pic" aria-selected="false">Pic</button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#status"
                type="button" role="tab" aria-controls="status" aria-selected="false">Status</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#admin"
                type="button" role="tab" aria-controls="admin" aria-selected="false">Admin</button>
        </li>


    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="item" role="tabpanel" aria-labelledby="item-tab">
           <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Item:</strong>
                    <input type="text" name="item" value="{{ $product->item }}" class="form-control" placeholder="Item">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Category:</strong>
                    <input type="text" name="category" value="{{ $product->category }}" class="form-control" placeholder="Category">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <input type="text" name="description" value="{{ $product->description }}" class="form-control" placeholder="Description">
                </div>
            </div>
            <br>
        </div>
        <div class="tab-pane fade p-3" id="quantity" role="tabpanel" aria-labelledby="quantity-tab">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Qty:</strong>
                    <input type="text" name="qty" value="{{ $product->qty }}" class="form-control" placeholder="qty">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Used:</strong>
                    <input type="text" name="used" value="{{ $product->used }}" class="form-control" placeholder="Used">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Remaining:</strong>
                    <input type="text" name="remaining" value="{{ $product->remaining }}" class="form-control" placeholder="Remaining">
                </div>
            </div>
            <br>
        </div>
        <div class="tab-pane fade p-3" id="renewal" role="tabpanel" aria-labelledby="renewal-tab">
            <div class="col-xs-2 col-sm-2 col-md-2 ">
                <div class="form-group">
                    <strong>Start date:</strong>
                    <input type="date" name="start_date" value="{{ $product->start_date }}" class="form-control" placeholder="Start date">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>End date:</strong>
                    <input type="date" name="end_date" value="{{ $product->end_date }}" class="form-control" placeholder="End date">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>Last bidding:</strong>
                    <input type="date" name="last_bidding" value="{{ $product->last_bidding }}" class="form-control" placeholder="Last bidding">                    
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>Next bidding:</strong>
                    <input type="date" name="next_bidding" value="{{ $product->next_bidding }}" class="form-control" placeholder="Next bidding">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>Renewal date:</strong>
                    <input type="date" name="renewal_date" value="{{ $product->renewal_date }}" class="form-control" placeholder="Renewal date">
                </div>
            </div>
            <br>
        </div>

        <div class="tab-pane fade p-3" id="email" role="tabpanel" aria-labelledby="email-tab">
            <div class="col-xs-2 col-sm-2 col-md-2 ">
                <div class="form-group">
                    <strong>Tgl email 1:</strong>
                    <input type="date" name="tgl_email1" value="{{ $product->tgl_email1 }}" class="form-control" placeholder="tgl_email1">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>Tgl email 2:</strong>
                    <input type="date" name="tgl_email2" value="{{ $product->tgl_email2 }}" class="form-control" placeholder="tgl_email2">
                </div>
            </div>
            <br>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <strong>Tgl email 3:</strong>
                    <input type="date" name="tgl_email3" value="{{ $product->tgl_email3 }}" class="form-control" placeholder="tgl_email3">
                </div>
            </div>
            <br>
             <div class="col-xs-8 col-sm-8 col-md-8">
                <div class="form-group">
                    <strong style="color: black;">Request date: </strong><br>
                    <strong style="color: red;">(update tanggal agar berhenti email ke pic dan admin)</strong>
                    
                </div>
            </div>
             <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">                    
                    <input type="date" name="request_date" value="{{ $product->request_date }}" class="form-control" placeholder="request_date">
                </div>
            </div>
            <br>
        </div>

         <div class="tab-pane fade p-3" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <div class="form-group">
                    <strong>vendor:</strong>
                    <input type="text" name="vendor" value="{{ $product->vendor }}" class="form-control" placeholder="vendor">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mata uang:</strong>
                    <input type="text" name="mata_uang" value="{{ $product->mata_uang }}" class="form-control" placeholder="Rp or Usd">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Amount excl vat:</strong>
                    <input type="text" name="amount_excl_vat" value="{{ $product->amount_excl_vat }}" class="form-control" placeholder="0">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Pr:</strong>
                    <input type="text" name="pr" value="{{ $product->pr }}" class="form-control" placeholder="pr">
                </div>
            </div>
            <br>
             <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Po:</strong>
                    <input type="text" name="po" value="{{ $product->po }}" class="form-control" placeholder="po">
                </div>
            </div>
            <br>
        </div>

        <div class="tab-pane fade p-3" id="pic" role="tabpanel" aria-labelledby="pic-tab">
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <div class="form-group">
                    <strong>Pic:</strong>
                    <input type="text" name="pic" value="{{ $product->pic }}" class="form-control" placeholder="pic">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mail pic:</strong>
                    <input type="email" name="mail_pic" value="{{ $product->mail_pic }}" class="form-control" placeholder="mail_pic">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Hp pic:</strong>
                    <input type="text" name="hp_pic" value="{{ $product->hp_pic }}" class="form-control" placeholder="hp_pic">
                </div>
            </div>
            
        </div>

         <div class="tab-pane fade p-3" id="status" role="tabpanel" aria-labelledby="status-tab">
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <div class="form-group">
                    <strong>Status:</strong>
                    <input type="text" name="status" value="{{ $product->status }}" class="form-control" placeholder="status">
                </div>
            </div>
            <br>
            <div class="col-xs-8 col-sm-8 col-md-8">
                <div class="form-group">                    
                    <strong style="color: black;">Date update status:  </strong><br>
                    <strong style="color: red;">(update dengan tanggal hari ini agar besok otomatis email status ke pic dan admin) </strong>                    
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">                    
                    <input type="date" name="date_update_status" value="{{ $product->date_update_status }}" class="form-control" placeholder="date_update_status">
                </div>
            </div>

            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>no_tiket:</strong>
                    <input type="text" name="no_tiket" value="{{ $product->no_tiket }}" class="form-control" placeholder="no_tiket">
                </div>
            </div>
             <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>remark:</strong>
                    <input type="text" name="remark" value="{{ $product->remark }}" class="form-control" placeholder="remark">
                </div>
            </div>
        </div>

        <div class="tab-pane fade p-3" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <div class="form-group">
                    <strong>Nama Admin:</strong>
                    <input type="text" name="nama_admin" value="{{ $product->nama_admin }}" class="form-control" placeholder="Nama">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email Admin:</strong>
                    <input type="email" name="email_admin" value="{{ $product->email_admin }}" class="form-control" placeholder="email">
                </div>
            </div>
            <br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Hp Admin:</strong>
                    <input type="text" name="hp_admin" value="{{ $product->hp_admin }}" class="form-control" placeholder="No Hp">
                </div>
            </div>
         </div>
        
        
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
        </div>

</div>
</form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const email1 = document.querySelector('input[name="tgl_email1"]');
        const email2 = document.querySelector('input[name="tgl_email2"]');
        const email3 = document.querySelector('input[name="tgl_email3"]');

        function formatDate(date) {
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        email1.addEventListener('change', function () {
            if (!this.value) return;

            // tgl_email2 = tgl_email1 + 30 days
            let date2 = new Date(this.value);
            date2.setDate(date2.getDate() + 30);
            email2.value = formatDate(date2);

            // tgl_email3 = tgl_email2 + 30 days
            let date3 = new Date(date2);
            date3.setDate(date3.getDate() + 30);
            email3.value = formatDate(date3);
        });
    });
</script>
