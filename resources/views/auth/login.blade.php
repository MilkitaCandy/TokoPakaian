<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - OUT FIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0" style="width: 100%; max-width: 400px; border-radius: 15px;">
        <div class="card-body p-5">
            <h3 class="text-center mb-4 fw-bolder">OUT FIT.</h3>
            @if(session('success')) <div class="alert alert-success small text-center">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger small text-center">{{ session('error') }}</div> @endif
            
            <form action="{{ url('/login') }}" method="POST">
                @csrf 
                <div class="mb-3"><input type="text" class="form-control p-3 bg-light" name="username" placeholder="Username" required></div>
                <div class="mb-4"><input type="password" class="form-control p-3 bg-light" name="password" placeholder="Password" required></div>
                <button type="submit" class="btn btn-dark w-100 p-3 fw-bold tracking-widest mb-3">MASUK</button>
            </form>
            <div class="text-center small"><a href="{{ url('/register') }}" class="text-muted text-decoration-none">Belum punya akun? Daftar di sini.</a></div>
        </div>
    </div>
</body>
</html>