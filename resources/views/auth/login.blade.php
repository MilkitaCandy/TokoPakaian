<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OUT FIT.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --pure-black: #000000; }
        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--pure-black);
            color: #ffffff;
            overflow-x: hidden;
        }
        
        /* Typography */
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* Layout Kiri: Foto Full Cover (Hanya muncul di Laptop) */
        .login-side-img {
            background: url('../img/asset/Login.jpg') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }

        /* Form Input Premium (Transparan & Border Tipis) */
        .form-control-custom {
            background-color: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0 !important; /* Kotak tajam */
            color: #ffffff !important;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .form-control-custom:focus {
            border-color: #ffffff;
            box-shadow: none;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        /* Tombol & Link */
        .btn-custom { 
            background-color: #ffffff;
            color: var(--pure-black);
            border-radius: 0;
            border: none;
            transition: transform 0.2s ease, opacity 0.3s ease; 
        }
        .btn-custom:hover { 
            opacity: 0.8;
            transform: translateY(-2px); 
        }
        
        .link-hover { 
            position: relative; 
            text-decoration: none; 
            color: rgba(255,255,255,0.7); 
            transition: color 0.3s ease; 
        }
        .link-hover:hover { color: #ffffff; }
        .link-hover::after {
            content: ''; position: absolute; width: 0; height: 1px;
            bottom: -2px; left: 0; background-color: #ffffff;
            transition: width 0.3s ease;
        }
        .link-hover:hover::after { width: 100%; }

        /* Separator "Or" kayak di referensi */
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: rgba(255,255,255,0.4);
            margin: 2rem 0;
        }
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .separator:not(:empty)::before { margin-right: 1em; }
        .separator:not(:empty)::after { margin-left: 1em; }
    </style>
</head>
<body>

    <!-- Container Full Width & No Padding -->
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            
            <!-- SISI KIRI: Foto (Di HP hilang berkat class 'd-none d-lg-block') -->
            <div class="col-lg-6 d-none d-lg-block login-side-img position-relative">
                <!-- Overlay Hitam Transparan Biar Elegan -->
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.3);"></div>
            </div>

            <!-- SISI KANAN: Form Login (Di HP jadi full width 'col-12') -->
            <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center bg-dark">
                
                <div class="w-100 px-4 px-md-5" style="max-width: 500px;">
                    
                    <div class="text-center mb-5">
                        <h1 class="fw-black tracking-widest text-uppercase m-0">OUT FIT.</h1>
                        <p class="small fw-light mt-3 text-white-50 lh-lg">
                            Enter your username to log in with your OUT FIT account or to create a new access.
                        </p>
                    </div>

                    @if(session('success')) 
                        <div class="alert rounded-0 small fw-bold text-center border-0 bg-success bg-opacity-25 text-white mb-4 tracking-wider">{{ session('success') }}</div> 
                    @endif
                    @if(session('error')) 
                        <div class="alert rounded-0 small fw-bold text-center border-0 bg-danger bg-opacity-25 text-white mb-4 tracking-wider">{{ session('error') }}</div> 
                    @endif
                    
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf 
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-custom" name="username" placeholder="Username*" required>
                        </div>
                        
                        <div class="mb-4">
                            <input type="password" class="form-control form-control-custom" name="password" placeholder="Password*" required>
                        </div>
                        
                        <button type="submit" class="btn btn-custom w-100 py-3 fw-bold tracking-wider text-uppercase mt-2">Accept and Continue</button>
                    </form>
                    
                    <!-- Garis 'Or' -->
                    <div class="separator small fw-bold text-uppercase">Or</div>
                    
                    <div class="text-center">
                        <a href="{{ url('/register') }}" class="btn btn-outline-light w-100 py-3 rounded-0 fw-bold tracking-wider text-uppercase transition text-opacity-100-hover opacity-75">Create Account</a>
                    </div>
                    
                    <div class="text-center mt-5 pt-3">
                        <a href="{{ url('/home') }}" class="link-hover small fw-medium tracking-wider text-uppercase">&larr; Back to Home</a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>