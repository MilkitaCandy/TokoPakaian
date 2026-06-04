<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Archive Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        :root { --pure-black: #000000; }
        body { font-family: 'Montserrat', sans-serif; background-color: #f4f5f7; color: var(--pure-black); }
        html { scroll-behavior: smooth; }

        /* === TYPOGRAPHY === */
        .fw-black { font-weight: 900; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* === TRUE BLACK OVERRIDE === */
        .bg-dark { background-color: var(--pure-black) !important; }
        .btn-dark { background-color: var(--pure-black) !important; border-color: var(--pure-black) !important; color: #fff !important; }
        .btn-dark:hover { background-color: #1a1a1a !important; border-color: #1a1a1a !important; }
        .btn-outline-dark { color: var(--pure-black) !important; border-color: var(--pure-black) !important; }
        .btn-outline-dark:hover { background-color: var(--pure-black) !important; color: #ffffff !important; }
        .text-dark { color: var(--pure-black) !important; }
        .border-dark { border-color: var(--pure-black) !important; }

        /* === NAVBAR EKSKLUSIF === */
        .navbar-custom { 
            background-color: var(--pure-black); 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
        }

        /* === FILTER BAR MAHAL === */
        .filter-section {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }
        .form-control-custom, .form-select-custom {
            border: 1px solid #ccc;
            border-radius: 0 !important;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--pure-black);
            box-shadow: none;
        }
        .input-group-text-custom {
            background: transparent;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 0;
            color: var(--pure-black);
        }

        /* === PRODUCT CARD === */
        .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
        .product-img-wrap { overflow: hidden; }
        .product-img { transition: transform 0.5s ease; }
        .product-card:hover .product-img { transform: scale(1.05); }
        .btn-lift { transition: transform 0.2s ease; }
        .btn-lift:hover { transform: translateY(-2px); }

        /* === PAGINATION SULTAN === */
        nav p.small.text-muted { display: none !important; }
        nav .justify-content-sm-between { justify-content: center !important; }
        nav .d-flex.justify-content-between.flex-fill.d-sm-none { display: none !important; }
        .pagination { margin-bottom: 0; }
        .pagination .page-link { 
            color: var(--pure-black); 
            border-radius: 0 !important; 
            border-color: #e0e0e0; 
            margin: 0 3px; 
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .pagination .page-item.active .page-link { 
            background-color: var(--pure-black); 
            border-color: var(--pure-black); 
            color: #ffffff; 
        }
        .pagination .page-link:hover:not(.active) {
            background-color: #f4f5f7;
            color: var(--pure-black);
        }

        .offcanvas-body::-webkit-scrollbar { width: 5px; }
        .offcanvas-body::-webkit-scrollbar-thumb { background-color: #dee2e6; }
        .offcanvas-body::-webkit-scrollbar-thumb:hover { background-color: #adb5bd; }
    </style>
</head>
<body>

    <!-- NAVBAR (Tetap Hitam Pekat) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top z-3">
        <div class="container">
            <a class="navbar-brand fw-black fs-4 tracking-widest text-uppercase" href="{{ url('/home') }}">OUT FIT.</a>
            
            <div class="d-flex align-items-center gap-4">
                <a href="{{ url('/home') }}" class="text-white text-decoration-none fw-bold small text-uppercase tracking-wider opacity-75 transition text-opacity-100-hover">
                    <i class="bi bi-arrow-left me-1"></i> Home
                </a>
                <a href="#cartSidebar" data-bs-toggle="offcanvas" class="text-white text-decoration-none fs-5 opacity-75 transition text-opacity-100-hover">
                    <i class="bi bi-bag"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- HEADER BANNER EKSKLUSIF (Sekarang Putih Bersih!) -->
    <div class="bg-white text-dark py-5 border-bottom border-secondary border-opacity-25">
        <div class="container text-center py-4">
            <p class="text-secondary fw-bold text-uppercase tracking-widest small mb-2">Explore The Full Collection</p>
            <h2 class="display-4 fw-black text-uppercase tracking-widest m-0">The Archive</h2>
        </div>
    </div>

    <!-- FILTER SECTION MAHAL (Nyatu sama Header) -->
    <div class="filter-section py-4 mb-5 shadow-sm bg-white">
        <div class="container">
            <form action="{{ url('/katalog') }}" method="GET" class="row g-3 align-items-end justify-content-center">
                
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Search Article</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control form-control-custom border-start-0 ps-0" placeholder="e.g. Boxy Hoodie..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Category</label>
                    <select name="kategori" class="form-select form-select-custom" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Atasan" {{ request('kategori') == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                        <option value="Bawahan" {{ request('kategori') == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                        <option value="Outerwear" {{ request('kategori') == 'Outerwear' ? 'selected' : '' }}>Outerwear</option>
                        <option value="Aksesoris" {{ request('kategori') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Sort By</label>
                    <select name="sort" class="form-select form-select-custom" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark rounded-0 fw-bold text-uppercase tracking-wider w-100 py-2 btn-lift">Filter</button>
                    @if(request('search') || request('sort') || request('kategori')) 
                        <a href="{{ url('/katalog') }}" class="btn btn-outline-danger rounded-0 py-2 px-3 btn-lift" title="Clear Filters"><i class="bi bi-x-lg"></i></a> 
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- GRID KATALOG -->
    <div class="container mb-5 pb-5">
        <div class="row g-4">
            @forelse ($dataPakaian as $pakaian)
            <div class="col-6 col-md-3">
                <div class="card product-card bg-white border border-secondary border-opacity-25 shadow-sm rounded-0 h-100 cursor-pointer">
                    
                    <div class="position-relative product-img-wrap border-bottom border-secondary border-opacity-25 bg-white">
                        <!-- Label Ketersediaan -->
                        @if($pakaian->stok <= 5 && $pakaian->stok > 0)
                            <span class="position-absolute top-0 start-0 m-3 badge bg-danger text-white rounded-0 px-2 py-1 fw-bold tracking-wider z-2 shadow-sm" style="font-size: 0.65rem;">LAST CHANCE</span>
                        @else
                            <span class="position-absolute top-0 start-0 m-3 badge bg-dark text-white rounded-0 px-3 py-1 fw-bold tracking-wider z-2 shadow-sm" style="font-size: 0.7rem;"></span>
                        @endif
                        
                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" class="product-img card-img-top object-fit-cover w-100 bg-white" style="aspect-ratio: 3/4;" alt="{{ $pakaian->nama_pakaian }}">
                        
                        <!-- Add to bag static bottom seperti di Home -->
                        <form action="{{ url('/cart/add') }}" method="POST" class="m-0 p-0 position-absolute bottom-0 start-0 w-100 z-3">
                            @csrf
                            <input type="hidden" name="pakaian_id" value="{{ $pakaian->_id }}">
                            <button type="submit" class="btn btn-dark w-100 rounded-0 py-2 fw-bold text-uppercase tracking-wider btn-lift" style="font-size: 0.85rem;">Add to Bag</button>
                        </form>
                    </div>
                    
                    <div class="card-body p-3 text-start d-flex flex-column justify-content-between">
                        <div>
                            <p class="text-secondary small mb-1 text-uppercase fw-semibold tracking-wider">{{ $pakaian->merk }}</p>
                            <h6 class="fw-bold mb-2 text-truncate" title="{{ $pakaian->nama_pakaian }}">{{ $pakaian->nama_pakaian }}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-2">
                            <p class="fw-bolder text-dark mb-0 fs-6">Rp {{ number_format($pakaian->harga, 0, ',', '.') }}</p>
                            <span class="text-muted fw-bold tracking-widest text-uppercase" style="font-size: 0.65rem;">{{ $pakaian->stok }} Left</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 my-5">
                <i class="bi bi-box-seam display-1 text-muted opacity-25 mb-3 d-block"></i>
                <h4 class="fw-black text-uppercase tracking-widest">Archive Empty</h4>
                <p class="text-secondary fw-medium">Koleksi dengan filter tersebut belum tersedia.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-outline-dark rounded-0 mt-3 px-5 py-3 fw-bold tracking-wider text-uppercase btn-lift">Reset Filters</a>
            </div>
            @endforelse
        </div>
        
        <!-- PAGINATION CUSTOM -->
        <div class="d-flex justify-content-center mt-5 pt-5">
            {{ $dataPakaian->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- FOOTER SIMPLE -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center text-muted small fw-medium tracking-wider">
            &copy; 2026 OUT FIT. All Rights Reserved.
        </div>
    </footer>

    <!-- OFFCANVAS KERANJANG -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
        <div class="offcanvas-header border-bottom py-4 bg-light">
            <h5 class="offcanvas-title fw-black tracking-widest text-uppercase fs-6" id="cartSidebarLabel">Your Bag</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0 bg-white">
            @php $total = 0; @endphp

            @if(session('cart') && count(session('cart')) > 0)
                <div class="flex-grow-1 overflow-auto">
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['harga'] * $details['quantity']; @endphp
                        <div class="p-4 border-bottom d-flex gap-4 align-items-center" style="transition: background 0.3s ease;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                            <img src="{{ asset('img/pakaian/' . $details['gambar']) }}" class="object-fit-cover shadow-sm border" style="width: 85px; height: 110px; aspect-ratio: 3/4;">
                            
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-2 text-truncate" style="font-size: 1rem; max-width: 150px;">{{ $details['nama_pakaian'] }}</h6>
                                <p class="text-secondary small fw-medium mb-3">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                                
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small fw-bold tracking-wider text-uppercase border px-2 py-1 bg-white">QTY: {{ $details['quantity'] }}</span>
                                    
                                    <form action="{{ url('/cart/remove') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="pakaian_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 shadow-none text-decoration-none" title="Remove Item"><i class="bi bi-trash fs-5"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 my-auto">
                    <i class="bi bi-bag display-3 text-dark opacity-25 mb-4 d-block"></i>
                    <p class="text-dark fw-bold tracking-wider text-uppercase mb-4">Bag is Empty</p>
                    <button type="button" class="btn btn-outline-dark rounded-0 px-5 py-3 fw-bold tracking-wider text-uppercase btn-lift" data-bs-dismiss="offcanvas">Continue Shopping</button>
                </div>
            @endif

            <div class="mt-auto p-4 bg-light border-top shadow-sm">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold tracking-wider text-uppercase small text-secondary">Subtotal</span>
                    <span class="fw-black fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ url('/checkout') }}" class="btn btn-dark w-100 rounded-0 py-3 fw-bold tracking-wider text-uppercase text-center text-decoration-none btn-lift {{ (!session('cart') || count(session('cart')) == 0) ? 'disabled' : '' }}">Secure Checkout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>