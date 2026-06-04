<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #F8F9FA; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* Animasi Card Premium */
        .order-card { transition: all 0.3s ease; border: 1px solid #E5E7EB !important; }
        .order-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; border-color: #212529 !important; }
        
        /* Efek Nota Fisik */
        .border-dashed { border-bottom: 2px dashed #E5E7EB; }
        .img-container { background-color: #F3F4F6; }
        
        .btn-lift { transition: transform 0.2s ease; }
        .btn-lift:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-black py-3 sticky-top z-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-black fs-4 tracking-widest m-0" href="{{ url('/home') }}">OUT FIT.</a>
            <div class="d-flex gap-4 align-items-center">
                <a href="{{ url('/katalog') }}" class="text-white text-decoration-none fw-semibold small text-uppercase tracking-wider transition opacity-75 text-opacity-100-hover d-none d-md-block">
                    <i class="bi bi-shop me-1"></i> Back to Shop
                </a>
                <a href="{{ url('/home') }}" class="btn btn-outline-light rounded-0 px-3 py-1 text-uppercase fw-bold text-sm btn-lift">
                    <i class="bi bi-x-lg"></i> Close
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5 mb-5" style="max-width: 850px;">
        
        <div class="mb-5 border-bottom border-2 border-dark pb-3 d-flex justify-content-between align-items-end">
            <div>
                <p class="text-secondary fw-bold mb-1 text-uppercase tracking-wider small">Your Archive</p>
                <h2 class="display-6 fw-black text-uppercase m-0">Order History</h2>
            </div>
        </div>

        @if(session('error')) 
            <div class="alert alert-danger rounded-0 small fw-bold tracking-wider mb-4 border-0 bg-danger bg-opacity-10 text-danger">{{ session('error') }}</div> 
        @endif

        @forelse($riwayat as $trx)
            <div class="card order-card shadow-sm rounded-0 mb-5 bg-white">
                
                <!-- Header Transaksi -->
                <div class="card-header bg-transparent border-dashed p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <span class="d-inline-block small text-muted mb-2 fw-semibold tracking-wider text-uppercase">
                            <i class="bi bi-calendar3 me-2"></i>{{ $trx->created_at ? $trx->created_at->format('d M Y • H:i') : 'N/A' }}
                        </span>
                        <h5 class="fw-black tracking-wider m-0">{{ $trx->invoice }}</h5>
                    </div>
                    
                    @php
                        $badgeClass = 'bg-light text-dark border'; 
                        if($trx->status == 'PENDING') $badgeClass = 'bg-warning text-dark border border-warning';
                        if($trx->status == 'DIPROSES') $badgeClass = 'bg-dark text-white border border-dark';
                        if($trx->status == 'DIKIRIM') $badgeClass = 'bg-info text-dark border border-info';
                        if($trx->status == 'SELESAI') $badgeClass = 'bg-success text-white border border-success';
                        if($trx->status == 'BATAL') $badgeClass = 'bg-danger text-white border border-danger';
                    @endphp
                    
                    <div class="text-md-end mt-2 mt-md-0 d-flex flex-column align-items-md-end gap-2">
                        <!-- Label Status Box -->
                        <span class="badge {{ $badgeClass }} rounded-0 px-4 py-2 fw-bold text-uppercase tracking-wider shadow-sm">
                            {{ $trx->status }}
                        </span>
                        
                        <!-- LOGIKA TOMBOL / KETERANGAN BAYAR -->
                        @if($trx->status == 'PENDING')
                            <a href="{{ url('/checkout/bayar-lagi/' . $trx->invoice) }}" class="btn btn-dark btn-sm rounded-0 fw-bold tracking-wider text-uppercase px-4 mt-1 btn-lift" style="font-size: 0.75rem; background-color: #000; color: #fff;">
                                Pay Now
                            </a>
                        @elseif(in_array($trx->status, ['DIPROSES', 'DIKIRIM', 'SELESAI']))
                            <span class="text-success small fw-bold tracking-wider text-uppercase mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-check-circle-fill me-1"></i> Payment Successful
                            </span>
                        @elseif($trx->status == 'BATAL')
                            <span class="text-danger small fw-bold tracking-wider text-uppercase mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-x-circle-fill me-1"></i> Cancelled
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-0">
                    @forelse(data_get($trx, 'items') ?? [] as $item)
                        <div class="d-flex gap-4 p-4 border-bottom align-items-center transition" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                            <div class="flex-shrink-0 img-container p-1 border">
                                <img src="{{ asset('img/pakaian/' . (data_get($item, 'gambar') ?? 'default.jpg')) }}" class="object-fit-cover" style="width: 70px; height: 95px; aspect-ratio: 3/4;" alt="Item">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-2 fs-5">{{ data_get($item, 'nama_pakaian') ?? 'Item Ghaib' }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small fw-semibold tracking-wider text-uppercase">QTY: {{ data_get($item, 'quantity') ?? 0 }}</span>
                                    <span class="fw-bold">Rp {{ number_format(data_get($item, 'harga') ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted fw-bold">
                            <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50"></i>
                            Gagal memuat detail item belanjaan.
                        </div>
                    @endforelse
                </div>

                <div class="card-footer bg-light border-0 p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <span class="small fw-bold text-uppercase tracking-wider text-secondary mb-2 mb-md-0">Total Amount</span>
                    <span class="fw-black fs-4 text-dark">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-5 my-5 bg-white border shadow-sm p-5">
                <i class="bi bi-box-seam display-1 text-secondary opacity-25 mb-4 d-block"></i>
                <h4 class="fw-black text-uppercase mb-3">No Order History</h4>
                <p class="text-secondary mb-4 mx-auto" style="max-width: 400px;">Lu belum pernah nyelesaiin pesanan pakai akun ini bro. Yuk cek rilisan terbaru kita!</p>
                <a href="{{ url('/katalog') }}" class="btn btn-dark rounded-0 px-5 py-3 fw-bold text-uppercase tracking-wider btn-lift" style="background-color: #000; color: #fff;">
                    Start Shopping
                </a>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>