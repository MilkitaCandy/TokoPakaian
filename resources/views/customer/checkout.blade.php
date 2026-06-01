<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Secure Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #FAFAFA; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-black py-3">
        <div class="container justify-content-center">
            <a class="navbar-brand fw-black fs-4 tracking-widest m-0" href="{{ url('/katalog') }}">OUT FIT.</a>
        </div>
    </nav>

    <div class="container py-5 mb-5">
        <div class="mb-4">
            <a href="{{ url('/katalog') }}" class="text-dark text-decoration-none fw-bold small text-uppercase tracking-wider"><i class="bi bi-arrow-left me-2"></i> Return to Cart</a>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <h4 class="fw-black text-uppercase mb-4">Shipping Details</h4>
                
                <form action="{{ url('/checkout/process') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Full Name</label>
                            <input type="text" name="nama" class="form-control rounded-0 py-2 shadow-none border-dark" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-0 py-2 shadow-none border-dark" value="{{ auth()->user() ? auth()->user()->email : '' }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Phone / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control rounded-0 py-2 shadow-none border-dark" required>
                        </div>

                        <div class="col-12">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Full Address</label>
                            <textarea name="alamat" class="form-control rounded-0 py-2 shadow-none border-dark" rows="3" placeholder="Nama Jalan, Gedung, RT/RW..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">City</label>
                            <input type="text" name="kota" class="form-control rounded-0 py-2 shadow-none border-dark" required>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Postal Code</label>
                            <input type="text" name="kode_pos" class="form-control rounded-0 py-2 shadow-none border-dark" required>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase tracking-wider fs-5">Place Order</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="bg-white p-4 border shadow-sm sticky-top" style="top: 20px;">
                    <h5 class="fw-black text-uppercase mb-4 pb-3 border-bottom">Order Summary</h5>
                    
                    @php $total = 0; @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['harga'] * $details['quantity']; @endphp
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="position-relative">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark border border-light">
                                    {{ $details['quantity'] }}
                                </span>
                                <img src="{{ asset('img/pakaian/' . $details['gambar']) }}" class="object-fit-cover border" style="width: 65px; height: 85px;">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $details['nama_pakaian'] }}</h6>
                                <p class="text-secondary small m-0">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <p class="fw-bold m-0" style="font-size: 0.9rem;">Rp {{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mb-2 mt-4 text-secondary">
                        <span class="small fw-bold text-uppercase">Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom text-secondary">
                        <span class="small fw-bold text-uppercase">Shipping</span>
                        <span>Calculated at next step</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-black text-uppercase tracking-wider fs-5">Total</span>
                        <span class="fw-black fs-4">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>