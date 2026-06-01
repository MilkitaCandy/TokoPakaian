<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Premium Clothing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        html { scroll-behavior: smooth; }
        .transition { transition: all 0.3s ease; }
        .text-opacity-100-hover:hover { opacity: 1 !important; }
    </style>
</head>
<body class="bg-light text-dark">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top border-bottom border-secondary py-3" style="--bs-bg-opacity: .95;">
        <div class="container">
            <a class="navbar-brand fw-bolder fs-3 text-uppercase" href="{{ url('/home') }}">OUT FIT.</a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1 text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-4 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link text-white fw-semibold text-uppercase" href="#new-arrivals">New Drops</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-semibold text-uppercase" href="{{ url('/katalog') }}">Collection</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-semibold text-uppercase" href="#">Lookbook</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0">
                    <div class="text-white text-end lh-1 pe-3 border-end border-secondary d-none d-md-block">
                        <span class="d-block small text-secondary mb-1">Welcome back,</span>
                        <span class="fw-bold text-uppercase">{{ auth()->user()->username }}</span>
                    </div>
                    
                    <!-- ICON RIWAYAT PESANAN (Kertas/Nota) -->
                    <a href="{{ url('/riwayat') }}" class="text-white text-decoration-none fs-5 transition" title="Riwayat Pesanan">
                        <i class="bi bi-receipt"></i>
                    </a>
                    
                    <!-- ICON BAG -->
                    <a href="#cartSidebar" data-bs-toggle="offcanvas" class="text-white text-decoration-none fs-5 transition" title="Keranjang Belanja">
                        <i class="bi bi-bag"></i>
                    </a>

                    <!-- ICON LOGOUT (Pintu Keluar Merah) -->
                    <a href="{{ url('/logout') }}" class="text-danger text-decoration-none fs-5 transition ms-2" title="Logout" onclick="return confirm('Yakin mau keluar dari OUT FIT bro?');">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="vh-100 d-flex align-items-center position-relative" 
            style="background: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?q=80&w=1600&auto=format&fit=crop') no-repeat center center; background-size: cover; background-attachment: fixed;">
        <div class="container text-center text-white mt-5">
            <p class="text-uppercase mb-3 fw-semibold text-secondary">Season 01 / Essentials</p>
            <h1 class="display-1 fw-bolder text-uppercase mb-4 lh-1">Define Your<br>Silhouette</h1>
            <p class="lead mb-5 fw-light mx-auto" style="max-width: 600px;">Koleksi washed loose denim dan boxy cut premium. Dirancang untuk proporsi yang sempurna dan kenyamanan maksimal di jalanan.</p>
            <a href="{{ url('/katalog') }}" class="btn btn-outline-light rounded-0 btn-lg px-5 py-3 fw-bold text-uppercase">Explore The Drop</a>
        </div>
    </header>

    <section class="container py-5 mt-5" id="new-arrivals">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <p class="text-secondary fw-bold mb-1 text-uppercase small">Just Landed</p>
                <h2 class="display-6 fw-bolder text-uppercase m-0">Latest Drops</h2>
            </div>
            <a href="{{ url('/katalog') }}" class="text-dark fw-bold text-uppercase text-decoration-none border-bottom border-2 border-dark pb-1 d-none d-md-block">View All Archive</a>
        </div>

        <div class="row g-4">
            @forelse ($dataPakaian as $pakaian)
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-transparent h-100">
                    <div class="position-relative bg-secondary bg-opacity-25">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark rounded-0 px-2 py-1 fw-bold z-2 shadow-sm">NEW</span>
                        
                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" class="card-img-top object-fit-cover w-100" style="aspect-ratio: 3/4;" alt="{{ $pakaian->nama_pakaian }}">
                        
                        <form action="{{ url('/cart/add') }}" method="POST" class="m-0 p-0 position-absolute bottom-0 start-0 w-100">
                            @csrf
                            <input type="hidden" name="pakaian_id" value="{{ $pakaian->_id }}">
                            <button type="submit" class="btn btn-light w-100 rounded-0 fw-bold text-uppercase border-top">Add to Bag</button>
                        </form>
                    </div>
                    <div class="card-body px-0 pt-3 pb-0 text-start">
                        <p class="text-secondary small mb-1 text-uppercase fw-semibold">{{ $pakaian->merk }}</p>
                        <h6 class="fw-bold mb-1 text-truncate">{{ $pakaian->nama_pakaian }}</h6>
                        <p class="fw-medium text-dark mb-0">Rp {{ number_format($pakaian->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-secondary fw-bold text-uppercase">Archived Empty. Awaiting New Drop.</p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-5 d-block d-md-none">
            <a href="{{ url('/katalog') }}" class="btn btn-dark rounded-0 px-4 py-3 fw-bold text-uppercase w-100">View All Archive</a>
        </div>
    </section>

    <section class="container py-5 my-5">
        <div class="row g-0 align-items-center bg-dark text-white">
            <div class="col-md-6 p-5 text-center text-md-start">
                <p class="fw-bold mb-2 text-secondary">EDITORIAL</p>
                <h2 class="display-5 fw-bolder text-uppercase mb-4 lh-1">Built For<br>The Streets.</h2>
                <p class="text-secondary mb-5 fw-light lh-lg">Material pilihan yang dirancang untuk durabilitas tinggi. Potongan boxy dan siluet longgar memberikan kebebasan bergerak tanpa mengorbankan estetika modern.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-outline-light rounded-0 px-5 py-3 fw-bold text-uppercase">Discover Story</a>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-1x1 h-100">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=1000&auto=format&fit=crop" class="img-fluid w-100 object-fit-cover" alt="Editorial">
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white pt-5 pb-4 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row mb-5 pb-4 border-bottom border-secondary">
                <div class="col-md-5 mb-4 mb-md-0">
                    <h3 class="fw-bolder mb-4">OUT FIT.</h3>
                    <p class="text-secondary small fw-light lh-lg pe-md-5">Redefining modern streetwear through carefully curated silhouettes. Establish your presence.</p>
                    <div class="d-flex gap-4 mt-4">
                        <a href="#" class="text-white fs-5 opacity-75 text-opacity-100-hover"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5 opacity-75 text-opacity-100-hover"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-white fs-5 opacity-75 text-opacity-100-hover"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h6 class="fw-bold text-uppercase mb-4">Shop</h6>
                    <ul class="list-unstyled d-flex flex-column gap-3 small">
                        <li><a href="{{ url('/katalog') }}" class="text-secondary text-decoration-none">All Collection</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">New Arrivals</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Denim</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Tops</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-uppercase mb-4">Stay Connected</h6>
                    <p class="text-secondary small fw-light mb-3">Join our newsletter for early access to new drops.</p>
                    <form class="d-flex">
                        <input type="email" class="form-control rounded-0 bg-transparent text-white border-secondary px-3 py-2 shadow-none" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-light rounded-0 px-4 fw-bold text-uppercase">Join</button>
                    </form>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-secondary">
                <p class="mb-2 mb-md-0">&copy; 2026 OUT FIT Worldwide.</p>
                <div class="d-flex gap-4">
                    <a href="#" class="text-secondary text-decoration-none">Terms</a>
                    <a href="#" class="text-secondary text-decoration-none">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title fw-bolder text-uppercase" id="cartSidebarLabel">Your Bag</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0">
            @php $total = 0; @endphp

            @if(session('cart') && count(session('cart')) > 0)
                <div class="flex-grow-1 overflow-auto">
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['harga'] * $details['quantity']; @endphp
                        <div class="p-3 border-bottom d-flex gap-3 align-items-center">
                            <img src="{{ asset('img/pakaian/' . $details['gambar']) }}" class="object-fit-cover" style="width: 80px; height: 100px;">
                            
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size: 0.9rem; max-width: 150px;">{{ $details['nama_pakaian'] }}</h6>
                                <p class="text-secondary small mb-2">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                                
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small fw-bolder">QTY: {{ $details['quantity'] }}</span>
                                    
                                    <form action="{{ url('/cart/remove') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="pakaian_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 shadow-none"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 my-auto">
                    <i class="bi bi-bag-x display-4 text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary fw-bold text-uppercase">Bag is Empty</p>
                    <button type="button" class="btn btn-outline-dark rounded-0 px-4 mt-2 fw-bold" data-bs-dismiss="offcanvas">CONTINUE SHOPPING</button>
                </div>
            @endif

            <div class="mt-auto p-4 bg-light border-top">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold text-uppercase">Subtotal</span>
                    <span class="fw-bolder fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ url('/checkout') }}" class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase text-center text-decoration-none {{ (!session('cart') || count(session('cart')) == 0) ? 'disabled' : '' }}">Secure Checkout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
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