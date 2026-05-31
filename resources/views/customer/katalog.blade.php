<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Archive Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #FAFAFA; color: #111; }
        .fw-black { font-weight: 900; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* Navbar */
        .navbar-custom { background-color: #000; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .premium-card { border: none; background: transparent; }
        .img-wrapper { overflow: hidden; position: relative; background-color: #EBEBEB; }
        .img-wrapper img { transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1); height: 280px; }
        .premium-card:hover .img-wrapper img { transform: scale(1.08); }
        
        .quick-add-btn {
            position: absolute; bottom: -50px; left: 0; width: 100%;
            background: rgba(255,255,255,0.9); color: #000; font-weight: 700;
            text-transform: uppercase; padding: 12px 0; transition: all 0.4s ease;
            opacity: 0; border: none; font-size: 0.9rem;
        }
        .premium-card:hover .quick-add-btn { bottom: 0; opacity: 1; }
        .quick-add-btn:hover { background: #111; color: #fff; }

        /* Trik Bersihin Pagination Laravel */
        nav p.small.text-muted { display: none !important; }
        nav .justify-content-sm-between { justify-content: center !important; }
        nav .d-flex.justify-content-between.flex-fill.d-sm-none { display: none !important; }
        .pagination { --bs-pagination-color: #000; --bs-pagination-active-bg: #000; --bs-pagination-active-border-color: #000; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand fw-black fs-4 tracking-widest" href="{{ url('/home') }}">OUT FIT.</a>
            <div class="d-flex align-items-center gap-4">
                <a href="{{ url('/home') }}" class="text-white text-decoration-none fw-bold small text-uppercase tracking-wider"><i class="bi bi-arrow-left me-2"></i> Back to Home</a>
            </div>
        </div>
    </nav>

    <div class="bg-light py-4 mb-4 border-bottom">
        <div class="container text-center">
            <h2 class="display-6 fw-black text-uppercase tracking-widest mb-2">The Archive</h2>
            <p class="text-muted fw-medium text-uppercase tracking-wider small m-0">Explore Our Full Collection</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="p-3 bg-white shadow-sm mb-4">
            <form action="{{ url('/katalog') }}" method="GET" class="row g-3 align-items-center">
                
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase tracking-wider mb-2">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent rounded-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control rounded-0 shadow-none border-start-0 ps-0" placeholder="Cari nama artikel..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase tracking-wider mb-2">Category</label>
                    <select name="kategori" class="form-select rounded-0 shadow-none" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <option value="Atasan" {{ request('kategori') == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                        <option value="Bawahan" {{ request('kategori') == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                        <option value="Outerwear" {{ request('kategori') == 'Outerwear' ? 'selected' : '' }}>Outerwear</option>
                        <option value="Aksesoris" {{ request('kategori') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase tracking-wider mb-2">Sort By</label>
                    <select name="sort" class="form-select rounded-0 shadow-none" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Paling Baru</option>
                        <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2 align-items-end mt-auto">
                    <button type="submit" class="btn btn-dark rounded-0 fw-bold w-100">FILTER</button>
                    @if(request('search') || request('sort') || request('kategori')) 
                        <a href="{{ url('/katalog') }}" class="btn btn-outline-danger rounded-0" title="Reset Filter"><i class="bi bi-x-lg"></i></a> 
                    @endif
                </div>
            </form>
        </div>
        
        <div class="row g-4">
            @forelse ($dataPakaian as $pakaian)
            <div class="col-6 col-md-3">
                <div class="card premium-card h-100 text-center">
                    <div class="img-wrapper">
                        @if($pakaian->stok <= 5)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark text-white rounded-0 px-2 py-1 fw-bold z-2 shadow-sm" style="font-size: 0.7rem;">LAST CHANCE</span>
                        @endif
                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" class="card-img-top object-fit-cover w-100" alt="{{ $pakaian->nama_pakaian }}">
                        <button class="quick-add-btn">Add to Bag</button>
                    </div>
                    <div class="card-body px-0 pt-3 pb-0 d-flex flex-column">
                        <p class="text-muted small tracking-widest text-uppercase fw-semibold m-0 text-start">{{ $pakaian->merk }}</p>
                        <h6 class="fw-bold my-2 text-truncate text-start">{{ $pakaian->nama_pakaian }}</h6>
                        
                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <p class="fw-bolder text-dark mb-0">Rp {{ number_format($pakaian->harga, 0, ',', '.') }}</p>
                            <span class="text-muted fw-bold tracking-widest" style="font-size: 0.65rem;">{{ $pakaian->stok }} LEFT</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold text-uppercase">Archived Empty</h4>
                <p class="text-muted">Barang dengan filter tersebut tidak ditemukan.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-dark rounded-0 mt-3 px-4 fw-bold">CLEAR FILTER</a>
            </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-5 pt-4 border-top">
            {{ $dataPakaian->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>