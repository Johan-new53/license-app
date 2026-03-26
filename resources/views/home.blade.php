@extends('layouts.app')

@section('content')

{{-- ── Session Alert ──────────────────────────────────── --}}
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Greeting Banner ─────────────────────────────────── --}}
<div class="dash-banner mb-4">
    <div class="dash-banner-inner">
        <div>
            <p class="dash-greeting">Selamat datang,</p>
            <h2 class="dash-username">{{ Auth::user()->name }}</h2>
            <div class="dash-meta d-flex flex-wrap gap-3 mt-2">
                <span><i class="fa-solid fa-envelope me-1 opacity-75"></i>{{ Auth::user()->email }}</span>
                <span><i class="fa-solid fa-layer-group me-1 opacity-75"></i>Level: {{ Auth::user()->level }}</span>
                <span>
                    <i class="fa-solid fa-shield-halved me-1 opacity-75"></i>
                    @foreach(Auth::user()->getRoleNames() as $role)
                        <span class="dash-badge">{{ $role }}</span>
                    @endforeach
                </span>
            </div>
        </div>
        <div class="dash-avatar-wrap d-none d-md-flex">
            <i class="fa-solid fa-user-tie"></i>
        </div>
    </div>
</div>

{{-- ── Quick-access Cards ──────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- ── LICENSE APP section ── --}}
    @can('product-list')
    <div class="col-12">
        <div class="dash-section-label">
            <i class="fa-solid fa-id-card fa-xs me-1"></i> License App
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('products.index') }}" class="dash-card dash-card--green">
            <div class="dash-card-icon"><i class="fa-solid fa-box"></i></div>
            <div>
                <div class="dash-card-title">Products</div>
                <div class="dash-card-sub">Kelola data produk lisensi</div>
            </div>
        </a>
    </div>
    @endcan

    {{-- ── PRF SUBMISSION section ── --}}
    @canany(['hardcopy-list','softcopy-list','automate-list','approval-list','payment-list'])
    <div class="col-12 @can('product-list') mt-2 @endcan">
        <div class="dash-section-label" style="--label-color: rgba(139,92,246,0.8);">
            <i class="fa-solid fa-file-invoice fa-xs me-1"></i> PRF Submission
        </div>
    </div>
    @endcanany

    @can('hardcopy-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('hardcopys.index') }}" class="dash-card dash-card--blue">
            <div class="dash-card-icon"><i class="fa-solid fa-book-bookmark"></i></div>
            <div>
                <div class="dash-card-title">Hard Copy</div>
                <div class="dash-card-sub">Dokumen fisik PRF</div>
            </div>
        </a>
    </div>
    @endcan

    @can('softcopy-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('softcopys.index') }}" class="dash-card dash-card--blue">
            <div class="dash-card-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
            <div>
                <div class="dash-card-title">Soft Copy</div>
                <div class="dash-card-sub">Upload file digital PRF</div>
            </div>
        </a>
    </div>
    @endcan

    @can('automate-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('automates.index') }}" class="dash-card dash-card--blue">
            <div class="dash-card-icon"><i class="fa-solid fa-robot"></i></div>
            <div>
                <div class="dash-card-title">Automate</div>
                <div class="dash-card-sub">PRF otomatis</div>
            </div>
        </a>
    </div>
    @endcan

    @can('approval-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('approvals.index') }}" class="dash-card dash-card--amber">
            <div class="dash-card-icon"><i class="fa-solid fa-thumbs-up"></i></div>
            <div>
                <div class="dash-card-title">Approval</div>
                <div class="dash-card-sub">Review & setujui PRF</div>
            </div>
        </a>
    </div>
    @endcan

    @can('payment-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('payments.index') }}" class="dash-card dash-card--teal">
            <div class="dash-card-icon"><i class="fa-solid fa-credit-card"></i></div>
            <div>
                <div class="dash-card-title">Payment</div>
                <div class="dash-card-sub">Status pembayaran PRF</div>
            </div>
        </a>
    </div>
    @endcan

    @can('finance-import')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('import') }}" class="dash-card dash-card--slate">
            <div class="dash-card-icon"><i class="fa-solid fa-file-import"></i></div>
            <div>
                <div class="dash-card-title">Import</div>
                <div class="dash-card-sub">Import data keuangan</div>
            </div>
        </a>
    </div>
    @endcan

    {{-- ── MASTER DATA ── --}}
    @canany(['bank-list','dept-list','reksumber-list','matauang-list','rektujuan-list','category-list','ppn-list'])
    <div class="col-12 mt-2">
        <div class="dash-section-label" style="--label-color: rgba(251,191,36,0.8);">
            <i class="fa-solid fa-database fa-xs me-1"></i> Master Data
        </div>
    </div>
    @endcanany

    @can('bank-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('bank.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div>
                <div class="dash-card-title">Bank</div>
                <div class="dash-card-sub">Data bank</div>
            </div>
        </a>
    </div>
    @endcan

    @can('dept-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('department.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-building"></i></div>
            <div>
                <div class="dash-card-title">Department</div>
                <div class="dash-card-sub">Data departemen</div>
            </div>
        </a>
    </div>
    @endcan

    @can('reksumber-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('reksumber.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <div class="dash-card-title">Rekening Sumber</div>
                <div class="dash-card-sub">Data rekening sumber</div>
            </div>
        </a>
    </div>
    @endcan

    @can('matauang-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('matauang.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-coins"></i></div>
            <div>
                <div class="dash-card-title">Mata Uang</div>
                <div class="dash-card-sub">Data mata uang</div>
            </div>
        </a>
    </div>
    @endcan

    @can('rektujuan-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('rektujuan.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-money-bill-transfer"></i></div>
            <div>
                <div class="dash-card-title">Rekening Tujuan</div>
                <div class="dash-card-sub">Data rekening tujuan</div>
            </div>
        </a>
    </div>
    @endcan

    @can('category-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('category.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-list"></i></div>
            <div>
                <div class="dash-card-title">Category</div>
                <div class="dash-card-sub">Data kategori</div>
            </div>
        </a>
    </div>
    @endcan

    @can('ppn-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('ppn.index') }}" class="dash-card dash-card--rose">
            <div class="dash-card-icon"><i class="fa-solid fa-percent"></i></div>
            <div>
                <div class="dash-card-title">PPN</div>
                <div class="dash-card-sub">Data tarif PPN</div>
            </div>
        </a>
    </div>
    @endcan

    {{-- ── ADMIN ── --}}
    @canany(['permission-list','role-list','user-list'])
    <div class="col-12 mt-2">
        <div class="dash-section-label" style="--label-color: rgba(148,163,184,0.9);">
            <i class="fa-solid fa-gear fa-xs me-1"></i> Admin
        </div>
    </div>
    @endcanany

    @can('permission-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('permissions.index') }}" class="dash-card dash-card--slate">
            <div class="dash-card-icon"><i class="fa-solid fa-key"></i></div>
            <div>
                <div class="dash-card-title">Permission</div>
                <div class="dash-card-sub">Kelola hak akses</div>
            </div>
        </a>
    </div>
    @endcan

    @can('role-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('roles.index') }}" class="dash-card dash-card--slate">
            <div class="dash-card-icon"><i class="fa-solid fa-user-shield"></i></div>
            <div>
                <div class="dash-card-title">Role</div>
                <div class="dash-card-sub">Kelola role pengguna</div>
            </div>
        </a>
    </div>
    @endcan

    @can('user-list')
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('users.index') }}" class="dash-card dash-card--slate">
            <div class="dash-card-icon"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="dash-card-title">Users</div>
                <div class="dash-card-sub">Kelola pengguna sistem</div>
            </div>
        </a>
    </div>
    @endcan

</div>

{{-- ── Styles scoped to this page ─────────────────────── --}}
<style>
/* Banner */
.dash-banner {
    background: linear-gradient(135deg, #001f5c 0%, #003d8a 50%, #0066b3 100%);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    color: #fff;
    box-shadow: 0 6px 24px rgba(0,31,92,0.45);
}
.dash-banner-inner { display:flex; justify-content:space-between; align-items:center; }
.dash-greeting { margin:0; font-size:0.85rem; opacity:0.65; font-weight:500; }
.dash-username  { margin:0; font-size:1.7rem; font-weight:700; letter-spacing:-0.5px; }
.dash-meta      { font-size:0.82rem; opacity:0.75; }
.dash-badge {
    display:inline-block;
    background:rgba(255,255,255,0.15);
    border-radius:30px;
    padding:1px 10px;
    font-size:0.78rem;
    font-weight:600;
}
.dash-avatar-wrap {
    width:72px; height:72px;
    border-radius:50%;
    background:rgba(255,255,255,0.12);
    border:2px solid rgba(255,255,255,0.2);
    font-size:2rem;
    color:rgba(255,255,255,0.6);
    align-items:center; justify-content:center;
    flex-shrink:0;
}

/* Section label */
.dash-section-label {
    font-size:0.72rem;
    font-weight:700;
    letter-spacing:0.7px;
    text-transform:uppercase;
    color: var(--label-color, rgba(52,211,153,0.9));
    display:flex;
    align-items:center;
    gap:0.3rem;
}

/* Quick-access cards */
.dash-card {
    display:flex;
    align-items:center;
    gap:1rem;
    padding:1.1rem 1.25rem;
    border-radius:14px;
    text-decoration:none !important;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    height:100%;
}
.dash-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.dash-card-icon {
    font-size:1.5rem;
    width:48px; height:48px;
    border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
    background:rgba(255,255,255,0.5);
}
.dash-card-title { font-weight:600; font-size:0.95rem; }
.dash-card-sub   { font-size:0.78rem; opacity:0.7; margin-top:1px; }

/* Color variants */
.dash-card--green { background:#d1fae5; border-color:#a7f3d0; color:#065f46; }
.dash-card--green .dash-card-icon { background:#a7f3d0; color:#047857; }

.dash-card--blue  { background:#dbeafe; border-color:#bfdbfe; color:#1e3a8a; }
.dash-card--blue .dash-card-icon  { background:#bfdbfe; color:#1d4ed8; }

.dash-card--amber { background:#fef3c7; border-color:#fde68a; color:#78350f; }
.dash-card--amber .dash-card-icon { background:#fde68a; color:#b45309; }

.dash-card--teal  { background:#ccfbf1; border-color:#99f6e4; color:#134e4a; }
.dash-card--teal .dash-card-icon  { background:#99f6e4; color:#0f766e; }

.dash-card--rose  { background:#ffe4e6; border-color:#fecdd3; color:#881337; }
.dash-card--rose .dash-card-icon  { background:#fecdd3; color:#be123c; }

.dash-card--slate { background:#f1f5f9; border-color:#e2e8f0; color:#1e293b; }
.dash-card--slate .dash-card-icon { background:#e2e8f0; color:#475569; }
</style>

@endsection
