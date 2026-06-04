<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - OUT FIT.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- SCRIPT MIDTRANS SNAP -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <style>
        :root { 
            --pure-black: #000000; 
            --pure-white: #ffffff;
            --border-color: #212529;
        }
        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: #fafafa; 
        }
        
        /* True Black Override */
        .bg-dark { background-color: var(--pure-black) !important; }
        .text-dark { color: var(--pure-black) !important; }
        .border-dark { border-color: var(--pure-black) !important; }
        
        /* Typography */
        .fw-black { font-weight: 900; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* Premium Button */
        .btn-dark { 
            background-color: var(--pure-black) !important; 
            border: 2px solid var(--pure-black) !important; 
            color: #fff !important; 
            transition: all 0.3s ease;
        }
        .btn-dark:hover { 
            background-color: transparent !important; 
            color: var(--pure-black) !important;
            transform: translateY(-2px);
        }
        
        /* Elegant Link */
        .link-elegant { 
            position: relative; 
            text-decoration: none; 
            color: var(--pure-black) !important;
            font-weight: 700;
        }
        .link-elegant::after {
            content: ''; position: absolute; width: 0; height: 1px;
            bottom: -2px; left: 0; background-color: var(--pure-black);
            transition: width 0.3s ease;
        }
        .link-elegant:hover::after { width: 100%; }

        /* Receipt Box Design */
        .receipt-box {
            border: 2px solid var(--pure-black);
            background-color: var(--pure-white);
            position: relative;
        }
        /* Aksen sudut ala nota */
        .receipt-box::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; width: 15px; height: 15px;
            border-top: 4px solid var(--pure-black);
            border-left: 4px solid var(--pure-black);
        }
        .receipt-box::after {
            content: '';
            position: absolute;
            bottom: -2px; right: -2px; width: 15px; height: 15px;
            border-bottom: 4px solid var(--pure-black);
            border-right: 4px solid var(--pure-black);
        }
        
        .border-dashed { border-bottom: 2px dashed var(--border-color); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

    <div class="receipt-box p-5 text-center shadow-sm" style="width: 100%; max-width: 460px;">
        
        <!-- HEADER BRAND -->
        <h1 class="fw-black tracking-widest text-uppercase m-0 mb-4 pb-4 border-dashed" style="font-size: 2.2rem;">OUT FIT.</h1>

        <!-- PAYMENT TITLE -->
        <div class="mb-5">
            <p class="text-secondary fw-bold mb-1 text-uppercase tracking-widest small" style="font-size: 0.65rem;">// Secure Checkout</p>
            <h2 class="display-6 fw-black text-uppercase m-0 tracking-wider">Payment</h2>
        </div>
        
        <!-- INVOICE DETAILS -->
        <div class="mb-4 text-start p-4 bg-light border border-dark rounded-0">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                <span class="small text-muted text-uppercase fw-bold tracking-wider" style="font-size: 0.7rem;">Document ID</span>
                <span class="fw-bold tracking-wider fs-6 text-dark">{{ $transaksi->invoice }}</span>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="small text-muted text-uppercase fw-bold tracking-wider" style="font-size: 0.7rem;">Total Amount</span>
                <span class="fw-black fs-4 tracking-wider text-dark">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- SANDBOX SIMULATOR GUIDE -->
        <div class="mb-5 text-start border-start border-3 border-dark ps-3 py-1">
            <span class="d-block small text-muted text-uppercase fw-bold tracking-wider mb-2" style="font-size: 0.65rem;">// Sandbox Tester Guide</span>
            <p class="small text-dark fw-medium mb-3 lh-sm" style="font-size: 0.75rem;">
                1. Click Pay Now & choose <strong>BCA Virtual Account</strong>.<br>
                2. Copy the VA number.<br>
                3. Paste it in the simulator to complete payment.
            </p>
            <a href="https://simulator.sandbox.midtrans.com/bca/va/index" target="_blank" class="link-elegant small text-uppercase fw-black tracking-widest text-dark" style="font-size: 0.7rem;">
                Open Simulator &#8599;
            </a>
        </div>

        <!-- ACTION BUTTONS -->
        <button id="pay-button" class="btn btn-dark w-100 py-3 fw-bold tracking-widest text-uppercase mb-4 rounded-0" style="font-size: 0.9rem;">
            Pay Now
        </button>
        
        <a href="{{ url('/riwayat') }}" class="link-elegant small text-uppercase tracking-wider opacity-75">
            Pay Later &rarr;
        </a>
    </div>

    <!-- SCRIPT ALERT -->
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran Berhasil! Pesanan lu bakal segera diproses.");
                    window.location.href = '/riwayat';
                },
                onPending: function(result){
                    alert("Bayar woi!");
                    window.location.href = '/riwayat';
                },
                onError: function(result){
                    alert("Pembayaran gagal atau ditolak.");
                },
                onClose: function(){
                    alert("Lu menutup pop-up sebelum bayar? Lu masih bisa bayar nanti lewat menu Riwayat.");
                }
            });
        };
    </script>
</body>
</html>