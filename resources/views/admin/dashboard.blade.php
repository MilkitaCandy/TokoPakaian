<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - OUT FIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
        
        /* Trik CSS Bersihin Pagination Laravel */
        nav p.small.text-muted { display: none !important; }
        nav .justify-content-sm-between { justify-content: center !important; }
        nav .d-flex.justify-content-between.flex-fill.d-sm-none { display: none !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark py-3 mb-4">
        <div class="container d-flex justify-content-between text-white">
            <span class="fw-bold">ADMIN PANEL</span>
            <a href="{{ url('/logout') }}" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        @if(session('success')) <div class="alert alert-success fw-bold">{{ session('success') }}</div> @endif
        
        <div class="row g-4 mb-5">
            <!-- Kolom Kiri: Form Tambah -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold mb-3">Tambah Stok</h5>
                    <form action="{{ url('/dashboard-admin') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="nama_pakaian" class="form-control mb-2" placeholder="Nama Pakaian" required>
                        <input type="text" name="merk" class="form-control mb-2" placeholder="Merk" required>
                        
                        <!-- UBAH JADI DROPDOWN BIAR ADMIN GAK BISA NGETIK SEMBARANGAN -->
                        <select name="kategori" class="form-select mb-2" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Atasan">Atasan</option>
                            <option value="Bawahan">Bawahan</option>
                            <option value="Outerwear">Outerwear</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>

                        <input type="number" name="tahun_rilis" class="form-control mb-2" placeholder="Tahun" required>
                        <input type="number" name="stok" class="form-control mb-2" placeholder="Stok" required>
                        <input type="number" name="harga" class="form-control mb-3" placeholder="Harga" required>
                        <input type="file" name="gambar" class="form-control mb-3" accept="image/*" required>
                        <button type="submit" class="btn btn-dark w-100">SIMPAN</button>
                    </form>
                </div>
            </div>
            
            <!-- Kolom Kanan: Tabel Data -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0 p-3 table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr><th>Gambar</th><th>Nama</th><th>Stok</th><th>Harga</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($dataPakaian as $pakaian)
                            <tr>
                                <td><img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" width="60" class="rounded object-fit-cover" style="height: 60px;"></td>
                                <td>
                                    <span class="fw-bold">{{ $pakaian->nama_pakaian }}</span> <br>
                                    <span class="badge bg-secondary">{{ $pakaian->merk }}</span>
                                </td>
                                <td>{{ $pakaian->stok }}</td>
                                <td class="fw-bold">Rp {{ number_format($pakaian->harga, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- Tombol Pemicu Modal Edit -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $pakaian->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <!-- Tombol Hapus -->
                                        <form action="{{ url('/dashboard-admin/' . $pakaian->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus produk ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- ================= MODAL EDIT UNTUK SETIAP ITEM ================= -->
                            <div class="modal fade" id="editModal{{ $pakaian->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title fw-bold">Edit Pakaian</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ url('/dashboard-admin/' . $pakaian->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf 
                                            @method('PUT') 
                                            
                                            <div class="modal-body p-4">
                                                <label class="small text-muted fw-bold">Nama Pakaian</label>
                                                <input type="text" name="nama_pakaian" class="form-control mb-2" value="{{ $pakaian->nama_pakaian }}" required>
                                                
                                                <label class="small text-muted fw-bold">Merk</label>
                                                <input type="text" name="merk" class="form-control mb-2" value="{{ $pakaian->merk }}" required>
                                                
                                                <label class="small text-muted fw-bold">Kategori</label>
                                                <!-- UBAH JADI DROPDOWN DAN OTOMATIS PILIH DATA SEBELUMNYA -->
                                                <select name="kategori" class="form-select mb-2" required>
                                                    <option value="Atasan" {{ $pakaian->kategori == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                                                    <option value="Bawahan" {{ $pakaian->kategori == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                                                    <option value="Outerwear" {{ $pakaian->kategori == 'Outerwear' ? 'selected' : '' }}>Outerwear</option>
                                                    <option value="Aksesoris" {{ $pakaian->kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                                </select>
                                                
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="small text-muted fw-bold">Tahun Rilis</label>
                                                        <input type="number" name="tahun_rilis" class="form-control mb-2" value="{{ $pakaian->tahun_rilis }}" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small text-muted fw-bold">Stok</label>
                                                        <input type="number" name="stok" class="form-control mb-2" value="{{ $pakaian->stok }}" required>
                                                    </div>
                                                </div>

                                                <label class="small text-muted fw-bold">Harga</label>
                                                <input type="number" name="harga" class="form-control mb-3" value="{{ $pakaian->harga }}" required>
                                                
                                                <div class="border p-2 rounded bg-light text-center mb-3">
                                                    <p class="small fw-bold mb-2">Gambar Saat Ini:</p>
                                                    <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" width="100" class="rounded shadow-sm">
                                                </div>
                                                
                                                <label class="small text-muted fw-bold">Ganti Gambar (Opsional)</label>
                                                <input type="file" name="gambar" class="form-control mb-3" accept="image/*">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-primary fw-bold">UPDATE DATA</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- ================= END MODAL EDIT ================= -->
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Button -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $dataPakaian->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BOOTSTRAP WAJIB ADA BIAR MODAL BISA MUNCUL -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <a href="https://dashboard.tawk.to/" target="_blank" class="btn btn-primary">
      Buka Panel Balas Chat
</a>
</body>
</html>