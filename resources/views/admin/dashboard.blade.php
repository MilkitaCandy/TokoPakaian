<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OUT FIT | Admin Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: #F5F5F5; 
            color: #111;
        }
        
        /* Typography Utility */
        .fw-black { font-weight: 900; }
        .tracking-widest { letter-spacing: 0.25em; }
        .tracking-wider { letter-spacing: 0.1em; }
        
        /* Form Focus Override (Matiin biru bawaan Bootstrap) */
        .form-control:focus, .form-select:focus {
            border-color: #000;
            box-shadow: none;
        }
        
        /* Custom Table Design */
        .table > :not(caption) > * > * {
            padding: 1rem 0.5rem;
            border-bottom-color: #E0E0E0;
        }

        /* Trik CSS Bersihin Pagination Laravel */
        nav p.small.text-muted { display: none !important; }
        nav .justify-content-sm-between { justify-content: center !important; }
        nav .d-flex.justify-content-between.flex-fill.d-sm-none { display: none !important; }
        .pagination { 
            --bs-pagination-color: #000; 
            --bs-pagination-active-bg: #000; 
            --bs-pagination-active-border-color: #000; 
        }
    </style>
</head>
<body>

    <!-- TOP NAVBAR (Control Center) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black py-3 sticky-top border-bottom border-secondary">
        <div class="container-fluid px-4 px-lg-5 d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-black fs-4 tracking-widest m-0" href="#">
                OUT FIT <span class="fw-normal fs-6 tracking-wider text-secondary">| ADMIN STUDIO</span>
            </a>
            
            <div class="d-flex gap-3 align-items-center">
                <!-- Tombol Kelola Pesanan -->
                <a href="{{ url('/admin/transaksi') }}" class="btn btn-outline-light rounded-0 fw-bold small text-uppercase tracking-wider">
                    <i class="bi bi-inbox me-2"></i> Orders
                </a>
                
                <!-- Tombol Balas Chat -->
                <a href="https://dashboard.tawk.to/" target="_blank" class="btn btn-light rounded-0 fw-bold small text-uppercase tracking-wider">
                    <i class="bi bi-chat-left-dots me-2"></i> Live Chat
                </a>
                
                <!-- Tombol Logout -->
                <a href="{{ url('/logout') }}" class="btn btn-danger rounded-0 fw-bold px-3 border-0" title="Logout">
                    <i class="bi bi-power"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="container-fluid px-4 px-lg-5 py-5">
        
        @if(session('success')) 
            <div class="alert alert-success rounded-0 fw-bold tracking-wider text-uppercase border-success border-2 shadow-sm mb-4">
                <i class="bi bi-check2-square me-2"></i> {{ session('success') }}
            </div> 
        @endif
        
        <div class="row g-5 mb-5">
            
            <!-- KOLOM KIRI: FORM TAMBAH (Desain Mewah) -->
            <div class="col-xl-3 col-lg-4">
                <div class="card rounded-0 border-dark shadow-sm">
                    <div class="card-header bg-black text-white rounded-0 py-3">
                        <h6 class="fw-bold m-0 text-uppercase tracking-wider">Add New Archive</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ url('/dashboard-admin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Item Name</label>
                            <input type="text" name="nama_pakaian" class="form-control rounded-0 mb-3 py-2 border-dark" placeholder="e.g. Washed Denim" required>
                            
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Brand</label>
                            <input type="text" name="merk" class="form-control rounded-0 mb-3 py-2 border-dark" placeholder="e.g. OUT FIT" required>
                            
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Category</label>
                            <select name="kategori" class="form-select rounded-0 mb-3 py-2 border-dark" required>
                                <option value="" disabled selected>Select Category...</option>
                                <option value="Atasan">Atasan</option>
                                <option value="Bawahan">Bawahan</option>
                                <option value="Outerwear">Outerwear</option>
                                <option value="Aksesoris">Aksesoris</option>
                            </select>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Year</label>
                                    <input type="number" name="tahun_rilis" class="form-control rounded-0 py-2 border-dark" placeholder="YYYY" required>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Stock</label>
                                    <input type="number" name="stok" class="form-control rounded-0 py-2 border-dark" placeholder="Qty" required>
                                </div>
                            </div>

                            <label class="small fw-bold text-uppercase text-secondary mb-1">Price (Rp)</label>
                            <input type="number" name="harga" class="form-control rounded-0 mb-3 py-2 border-dark" placeholder="0" required>
                            
                            <label class="small fw-bold text-uppercase text-secondary mb-1">Product Image</label>
                            <input type="file" name="gambar" class="form-control rounded-0 mb-4 border-dark" accept="image/*" required>
                            
                            <button type="submit" class="btn btn-dark rounded-0 w-100 py-3 fw-bold text-uppercase tracking-wider">Publish Item</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- KOLOM KANAN: TABEL DATA (Desain Minimalis) -->
            <div class="col-xl-9 col-lg-8">
                <div class="card rounded-0 border-0 shadow-sm bg-white p-4">
                    <div class="d-flex justify-content-between align-items-end border-bottom border-dark pb-3 mb-3">
                        <h4 class="fw-black text-uppercase m-0 tracking-wider">Inventory Master</h4>
                        <span class="text-secondary fw-bold small text-uppercase">Total: {{ count($dataPakaian) }} Items</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-dark text-uppercase small tracking-wider">
                                <tr>
                                    <th class="py-3 px-3">Visual</th>
                                    <th>Detail Artikel</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th class="text-end px-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataPakaian as $pakaian)
                                <tr>
                                    <td class="px-3">
                                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" class="object-fit-cover border border-secondary" style="width: 70px; height: 90px; aspect-ratio: 3/4;">
                                    </td>
                                    <td>
                                        <span class="fw-bold d-block mb-1" style="font-size: 1.1rem;">{{ $pakaian->nama_pakaian }}</span>
                                        <span class="badge bg-secondary rounded-0 tracking-widest text-uppercase">{{ $pakaian->merk }}</span>
                                        <span class="small text-muted ms-2">{{ $pakaian->tahun_rilis }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-uppercase small">{{ $pakaian->kategori }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bolder fs-5 {{ $pakaian->stok <= 5 ? 'text-danger' : 'text-dark' }}">{{ $pakaian->stok }}</span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($pakaian->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end px-3">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-sm btn-dark rounded-0 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $pakaian->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            <!-- Form Hapus -->
                                            <form action="{{ url('/dashboard-admin/' . $pakaian->id) }}" method="POST" onsubmit="return confirm('Yakin mau menghancurkan produk ini dari arsip?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-0 px-3"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT (Desain Menyesuaikan Tema) -->
                                <div class="modal fade" id="editModal{{ $pakaian->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-0 border-dark shadow-lg">
                                            <div class="modal-header bg-black text-white rounded-0 border-0">
                                                <h5 class="modal-title fw-bold text-uppercase tracking-wider">Update Archive</h5>
                                                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ url('/dashboard-admin/' . $pakaian->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('PUT') 
                                                <div class="modal-body p-4">
                                                    
                                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Nama Pakaian</label>
                                                    <input type="text" name="nama_pakaian" class="form-control rounded-0 mb-3 py-2 border-dark" value="{{ $pakaian->nama_pakaian }}" required>
                                                    
                                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Merk</label>
                                                    <input type="text" name="merk" class="form-control rounded-0 mb-3 py-2 border-dark" value="{{ $pakaian->merk }}" required>
                                                    
                                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Kategori</label>
                                                    <select name="kategori" class="form-select rounded-0 mb-3 py-2 border-dark" required>
                                                        <option value="Atasan" {{ $pakaian->kategori == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                                                        <option value="Bawahan" {{ $pakaian->kategori == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                                                        <option value="Outerwear" {{ $pakaian->kategori == 'Outerwear' ? 'selected' : '' }}>Outerwear</option>
                                                        <option value="Aksesoris" {{ $pakaian->kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                                    </select>
                                                    
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="small fw-bold text-uppercase text-secondary mb-1">Tahun Rilis</label>
                                                            <input type="number" name="tahun_rilis" class="form-control rounded-0 py-2 border-dark" value="{{ $pakaian->tahun_rilis }}" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="small fw-bold text-uppercase text-secondary mb-1">Stok</label>
                                                            <input type="number" name="stok" class="form-control rounded-0 py-2 border-dark" value="{{ $pakaian->stok }}" required>
                                                        </div>
                                                    </div>

                                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Harga (Rp)</label>
                                                    <input type="number" name="harga" class="form-control rounded-0 mb-4 py-2 border-dark" value="{{ $pakaian->harga }}" required>
                                                    
                                                    <div class="border border-secondary p-3 bg-light text-center mb-3">
                                                        <p class="small fw-bold text-uppercase tracking-wider mb-2">Visual Saat Ini</p>
                                                        <img src="{{ asset('img/pakaian/' . $pakaian->gambar) }}" class="object-fit-cover border border-dark" style="width: 80px; height: 100px;">
                                                    </div>
                                                    
                                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Ganti Visual (Opsional)</label>
                                                    <input type="file" name="gambar" class="form-control rounded-0 border-dark" accept="image/*">
                                                </div>
                                                
                                                <div class="modal-footer bg-light border-top-0">
                                                    <button type="button" class="btn btn-outline-dark rounded-0 fw-bold px-4" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-dark rounded-0 fw-bold px-4 tracking-wider text-uppercase">Update Data</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL EDIT -->

                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-box2 display-1 d-block mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-uppercase">Arsip Kosong</h5>
                                        <p>Belum ada produk yang di-upload ke sistem.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                        {{ $dataPakaian->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>