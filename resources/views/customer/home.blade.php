<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Archive & Essentials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Ganti font ke Space Grotesk buat vibes yang lebih industrial/streetwear -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f8f8f8;
            --text-main: #111111;
            --accent: #e5e5e5;
        }

        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Glassmorphism Navbar */
        .glass-nav {
            background: rgba(0, 0, 0, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(17, 17, 17, 0.1);
        }

        .nav-link {
            color: #ffffff !important;
            font-size: 0.85rem;
            letter-spacing: 1px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 100%; transform: scaleX(0); height: 1px;
            bottom: 0; left: 0;
            background-color: #ffffff;
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* Hero Section - Asymmetrical */
        .hero-section {
            min-height: 90vh;
            padding-top: 100px;
            display: flex;
            align-items: center;
        }
        
        .hero-title {
            font-size: clamp(3rem, 8vw, 7rem);
            line-height: 0.9;
            letter-spacing: -2px;
        }

        /* Product Catalog - Ditch the Bootstrap Cards */
        .product-item {
            position: relative;
            cursor: pointer;
        }

        .product-img-wrapper {
            overflow: hidden;
            background: #ebebeb;
            position: relative;
        }

        .product-img-wrapper img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .product-item:hover .product-img-wrapper img {
            transform: scale(1.04);
        }

        /* Hover Action Button (Lebih natural, muncul dari bawah) */
        .quick-add {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            background: var(--text-main);
            color: #fff;
            border: none;
            padding: 12px 0;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: bottom 0.3s ease;
        }

        .product-item:hover .quick-add {
            bottom: 0;
        }

        /* Editorial Section */
        .editorial-img {
            aspect-ratio: 4/5;
            object-fit: cover;
            filter: grayscale(20%);
        }

        /* Custom Overrides */
        .btn-outline-dark {
            border-radius: 0;
            border-width: 1px;
            letter-spacing: 1px;
            font-size: 0.85rem;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #111; }
    </style>
</head>
<body>

    <!-- Glass Navbar -->
    <nav class="navbar navbar-expand-lg glass-nav fixed-top py-3">
        <div class="container-fluid px-4 px-md-5">
            <a class="navbar-brand fw-bold fs-4 text-uppercase tracking-tighter text-white" href="{{ url('/home') }}">OUT FIT.</a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-3 gap-md-5 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase" href="#new-arrivals">Archive</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase" href="{{ url('/katalog') }}">Collection</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase" href="#">Journal</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0">
                    @guest
                    <a href="{{ url('/login') }}" class="nav-link fw-semibold text-uppercase">Log In</a>
                    @endguest

                    @auth
                    <div class="d-none d-md-block text-end lh-1">
                        <span class="d-block text-white-50" style="font-size: 0.7rem;">ACCOUNT</span>
                        <span class="fw-bold text-uppercase text-white" style="font-size: 0.85rem;">{{ auth()->user()->username }}</span>
                    </div>
                    <a href="{{ url('/riwayat') }}" class="text-white fs-5"><i class="bi bi-receipt"></i></a>
                    @endauth
                    
                    <!-- Keranjang -->
                    <a href="#cartSidebar" data-bs-toggle="offcanvas" class="text-white position-relative">
                        <i class="bi bi-bag fs-5"></i>
                        @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-white text-dark p-1" style="font-size: 0.6rem;">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                    <a href="{{ url('/logout') }}" class="text-white fs-5 ms-2" onclick="return confirm('Log out session?');"><i class="bi bi-box-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section container-fluid px-4 px-md-5">
        <div class="row w-100 align-items-center">
            <div class="col-md-7 mb-5 mb-md-0 z-1">
                <p class="fw-semibold text-uppercase mb-2" style="letter-spacing: 2px; font-size: 0.8rem;">Drop 01 — 2026</p>
                <h1 class="hero-title fw-bold text-uppercase mb-4">Redefine<br>The Fit.</h1>
                <p class="mb-5 text-secondary" style="max-width: 450px; font-size: 1.1rem; line-height: 1.6;">
                    Curated silhouettes focused on washed denim and boxy cuts. Crafted for the daily grind, built to last.
                </p>
                <a href="{{ url('/katalog') }}" class="btn btn-dark rounded-0 px-5 py-3 fw-bold text-uppercase" style="letter-spacing: 1px;">Explore Drop</a>
            </div>
            <div class="col-md-5 position-relative">
                <!-- Gambar hero yang lebih "editorial" -->
                <img src="https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?q=80&w=1600&auto=format&fit=crop" class="img-fluid w-100 object-fit-cover" style="height: 70vh; filter: contrast(1.1) brightness(0.9);" alt="Hero Look">
            </div>
        </div>
    </header>

    <!-- Latest Arrivals (Tanpa Box Kaku) -->
    <section class="container-fluid px-4 px-md-5 py-5 mt-5" id="new-arrivals">
        <div class="d-flex justify-content-between align-items-end mb-5 border-bottom border-dark pb-3">
            <h2 class="fw-bold text-uppercase m-0" style="font-size: 2rem;">Latest Archive</h2>
            <a href="{{ url('/katalog') }}" class="text-dark fw-semibold text-uppercase text-decoration-none" style="font-size: 0.85rem; letter-spacing: 1px;">View All</a>
        </div>

        <div class="row g-4">
            @forelse ($dataPakaian as $pakaian)
            <div class="col-6 col-md-3">
                <div class="product-item">
                    <div class="product-img-wrapper mb-3">
                        <span class="position-absolute top-0 left-0 m-2 badge bg-white text-dark rounded-0 px-2 py-1 fw-bold z-2 border border-dark" style="font-size: 0.65rem;">NEW</span>
                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" alt="{{ $pakaian->nama_pakaian }}">
                        
                        <form action="{{ url('/cart/add') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <input type="hidden" name="pakaian_id" value="{{ $pakaian->_id }}">
                            <button type="submit" class="quick-add">Add to Bag</button>
                        </form>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem;">{{ $pakaian->merk }}</p>
                            <h6 class="fw-bold mb-1 text-uppercase" style="font-size: 0.9rem;">{{ $pakaian->nama_pakaian }}</h6>
                        </div>
                        <p class="fw-bold mb-0" style="font-size: 0.9rem;">Rp {{ number_format($pakaian->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 py-5 border border-dark border-opacity-25 text-center">
                <p class="text-muted fw-semibold text-uppercase m-0">Archive Empty. Awaiting Restock.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Editorial Section -->
    <section class="container-fluid px-4 px-md-5 py-5 my-5">
        <div class="row g-0 align-items-center">
            <div class="col-md-6 pe-md-5 mb-5 mb-md-0">
                <img src="../img/asset/Editorial.jpg" class="w-100 editorial-img" alt="Lookbook">
            </div>
            <div class="col-md-5 ps-md-4">
                <p class="fw-bold mb-3 text-uppercase text-muted" style="letter-spacing: 2px;">Editorial 001</p>
                <h2 class="fw-bold text-uppercase mb-4" style="font-size: 3.5rem; line-height: 0.9;">Shape<br>& Form.</h2>
                <p class="text-secondary mb-5" style="font-size: 1.1rem; line-height: 1.6;">
                    Merancang ulang pergerakan di jalanan. Proporsi oversized dengan sentuhan jahitan kokoh, memastikan setiap pieces siap menemani rutinitas keras tanpa kehilangan estetika *clean* dan modern.
                </p>
                <a href="{{ url('/katalog') }}" class="btn btn-outline-dark rounded-0 px-5 py-3 fw-bold text-uppercase">Read Journal</a>
            </div>
        </div>
    </section>

    <!-- Minimalist Footer -->
    <footer class="bg-white text-dark pt-5 pb-4 border-top border-dark mt-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="row mb-5">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h3 class="fw-bold mb-3 text-uppercase tracking-tighter">OUT FIT.</h3>
                    <p class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Solo Belum Tidur, ID — Worldwide</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.85rem;">Explore</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2" style="font-size: 0.85rem;">
                        <li><a href="{{ url('/katalog') }}" class="text-secondary text-decoration-none">Collection</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Journal</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Sizing Guide</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4 ms-auto">
                    <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.85rem;">Newsletter</h6>
                    <form class="d-flex border-bottom border-dark pb-2">
                        <input type="email" class="form-control border-0 px-0 shadow-none rounded-0 bg-transparent" placeholder="EMAIL ADDRESS" required style="font-size: 0.85rem;">
                        <button type="submit" class="btn btn-link text-dark fw-bold text-decoration-none px-0 text-uppercase" style="font-size: 0.85rem;">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-end" style="font-size: 0.7rem;">
                <p class="mb-0 text-secondary text-uppercase">&copy; 2026 OUT FIT.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-dark fs-5"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Offcanvas Cart -->
    <div class="offcanvas offcanvas-end border-start border-dark" tabindex="-1" id="cartSidebar">
        <div class="offcanvas-header border-bottom border-dark py-3">
            <h5 class="offcanvas-title fw-bold text-uppercase fs-6">Bag</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0">
            @php $total = 0; @endphp
            @if(session('cart') && count(session('cart')) > 0)
                <div class="flex-grow-1 overflow-auto px-3">
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['harga'] * $details['quantity']; @endphp
                        <div class="py-3 border-bottom border-light d-flex gap-3 align-items-center">
                            <img src="{{ asset('img/pakaian/' . $details['gambar']) }}" class="object-fit-cover bg-light" style="width: 70px; height: 90px;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-uppercase" style="font-size: 0.8rem;">{{ $details['nama_pakaian'] }}</h6>
                                <p class="text-muted mb-2" style="font-size: 0.8rem;">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold" style="font-size: 0.75rem;">QTY: {{ $details['quantity'] }}</span>
                                    <form action="{{ url('/cart/remove') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="pakaian_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 shadow-none text-decoration-none" style="font-size: 0.75rem;">REMOVE</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 my-auto">
                    <p class="text-muted fw-bold text-uppercase" style="font-size: 0.85rem;">Bag is Empty</p>
                </div>
            @endif

            <div class="mt-auto p-4 border-top border-dark bg-white">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold text-uppercase fs-6">Subtotal</span>
                    <span class="fw-bold fs-6">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ url('/checkout') }}" class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase {{ (!session('cart') || count(session('cart')) == 0) ? 'disabled' : '' }}" style="letter-spacing: 1px;">Checkout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Tawk.to Script -->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {};
        Tawk_API.onLoad = function() {
            @auth
            Tawk_API.setAttributes({
                'name': '{{ auth()->user()->nama ?? auth()->user()->username }}',
                'email': '{{ auth()->user()->email ?? "user@outfit.com" }}'
            }, function(error) {});
            @else
            Tawk_API.setAttributes({ 'name': 'Guest' }, function(error) {});
            @endauth
        };
        var Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/6a1ad55faeb8521c2817aeca/1jpsd2vqj';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
</body>
</html>