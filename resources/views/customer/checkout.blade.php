<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Secure Checkout</title>
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
        .bg-black { background-color: var(--pure-black) !important; }
        .btn-dark { background-color: var(--pure-black) !important; border-color: var(--pure-black) !important; color: #fff !important; }
        .btn-dark:hover { background-color: #1a1a1a !important; border-color: #1a1a1a !important; }
        .text-dark { color: var(--pure-black) !important; }

        /* === PREMIUM INPUT FIELD === */
        .form-control-custom {
            border: 1px solid #ccc;
            border-radius: 0 !important; /* Tajam bersiku */
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background-color: #ffffff;
        }
        .form-control-custom:focus {
            border-color: var(--pure-black);
            box-shadow: none;
            background-color: #ffffff;
        }

        /* === SULIST SIDEBAR SUMMARY === */
        .summary-box {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 0 !important;
        }

        /* Badge Quantity Kotak */
        .badge-square {
            border-radius: 0 !important;
            font-size: 0.7rem;
            padding: 0.45em 0.65em;
            background-color: var(--pure-black);
        }

        /* Animasi Tombol Lift */
        .btn-lift { transition: transform 0.2s ease; }
        .btn-lift:hover { transform: translateY(-2px); }
        
        /* Link Back */
        .link-elegant { position: relative; text-decoration: none; transition: color 0.3s ease; }
        .link-elegant::after {
            content: ''; position: absolute; width: 0; height: 1px;
            bottom: -2px; left: 0; background-color: var(--pure-black);
            transition: width 0.3s ease;
        }
        .link-elegant:hover::after { width: 100%; }
    </style>
</head>
<body>

    <!-- NAVBAR ULTRA CLEAN -->
    <nav class="navbar navbar-dark bg-black py-4 sticky-top">
        <div class="container justify-content-center">
            <a class="navbar-brand fw-black fs-3 tracking-widest m-0 text-uppercase" href="{{ url('/katalog') }}">OUT FIT.</a>
        </div>
    </nav>

    <div class="container py-5 mb-5">
        <!-- BACK LINK WITH ANIMATION -->
        <div class="mb-5">
            <a href="{{ url('/katalog') }}" class="text-dark link-elegant fw-bold small text-uppercase tracking-wider">
                <i class="bi bi-arrow-left me-2"></i> Return to Cart
            </a>
        </div>

        <div class="row g-5">
            <!-- SISI KIRI: SHIPPING DETAILS -->
            <div class="col-lg-7">
                <div class="mb-4">
                    <h4 class="fw-black text-uppercase tracking-wider m-0">Shipping Details</h4>
                    <p class="text-secondary small fw-medium text-uppercase tracking-wider mt-1">Please fill your delivery point information</p>
                </div>
                
                <form action="{{ url('/checkout/process') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Full Name</label>
                            <input type="text" name="nama" class="form-control form-control-custom" placeholder="e.g. Fais Abimanyu" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-custom" value="{{ auth()->user() ? auth()->user()->email : '' }}" placeholder="name@domain.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Phone / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control form-control-custom" placeholder="e.g. 08123456789" required>
                        </div>

                        <div class="col-12">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Full Address</label>
                            <textarea name="alamat" class="form-control form-control-custom" rows="3" placeholder="Street Name, Building, Unit Number, RT/RW..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">City</label>
                            <input type="text" name="kota" class="form-control form-control-custom" placeholder="e.g. Surakarta" required>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase tracking-wider text-secondary mb-2">Postal Code</label>
                            <input type="text" name="kode_pos" class="form-control form-control-custom" placeholder="e.g. 57123" required>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase tracking-wider fs-5 btn-lift shadow-sm">
                                Place Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SISI KANAN: ORDER SUMMARY BOX -->
            <div class="col-lg-5">
                <div class="summary-box p-4 sticky-top shadow-sm" style="top: 110px;">
                    <h5 class="fw-black text-uppercase tracking-wider mb-4 pb-3 border-bottom">Order Summary</h5>
                    
                    @php $total = 0; @endphp
                    <!-- UBAH BARIS INI (Tambahin , [] di dalem session-nya) -->
@foreach(session('cart', []) as $id => $details)
    @php $total += $details['harga'] * $details['quantity']; @endphp
    <div class="d-flex gap-3 mb-3 pb-3 border-bottom border-light align-items-center">
        <!-- ... sisa kodingan isi loop lu di bawahnya tetap sama ... -->
                            <div class="position-relative">
                                <!-- Badge Diubah Jadi Kotak Hitam Tipis -->
                                <span class="position-absolute top-0 start-100 translate-middle badge badge-square border border-light">
                                    {{ $details['quantity'] }}
                                </span>
                                <img src="{{ asset('img/pakaian/' . $details['gambar']) }}" class="object-fit-cover border" style="width: 70px; height: 90px; aspect-ratio: 3/4;">
                            </div>
                            <div class="flex-grow-1 ps-2">
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size: 0.95rem; max-width: 180px;">{{ $details['nama_pakaian'] }}</h6>
                                <p class="text-secondary small m-0 fw-medium">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <p class="fw-bold m-0" style="font-size: 0.95rem;">Rp {{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <!-- BREAKDOWN PRICE -->
                    <div class="d-flex justify-content-between mb-2 mt-4 text-secondary fw-medium">
                        <span class="small fw-bold text-uppercase tracking-wider">Subtotal</span>
                        <span class="small font-monospace">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom text-secondary fw-medium">
                        <span class="small fw-bold text-uppercase tracking-wider">Shipping</span>
                        <span class="small text-uppercase tracking-wide" style="font-size: 0.75rem;">Calculated at next step</span>
                    </div>
                    
                    <!-- TOTAL AREA -->
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <span class="fw-black text-uppercase tracking-widest fs-5">Total</span>
                        <span class="fw-black fs-4 tracking-wider">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>