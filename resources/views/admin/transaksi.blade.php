<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pesanan Masuk</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f4f6f9; 
        }
        
        /* Tambahan CSS biar spasi tabel lebih lega dan teks yang turun ke bawah tetep enak dibaca */
        .table > :not(caption) > * > * { 
            padding: 1.25rem 1rem; 
            vertical-align: top; /* Bikin konten rata atas semua pas teksnya turun ke bawah */
        }
    </style>
</head>
<body>

    <div class="container-fluid py-5 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Manajemen Pesanan</h3>
            <a href="{{ url('/dashboard-admin') }}" class="btn btn-dark fw-bold shadow-sm">Kembali ke Dashboard</a>
        </div>

        @php /* Menampilkan notifikasi umpan balik sukses atau error dari sistem Controller */ @endphp
        @if(session('success'))
            <div class="alert alert-success fw-bold shadow-sm border-0">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger fw-bold shadow-sm border-0">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless m-0">
                        <thead class="table-dark">
                            <tr>
                                @php /* Pembagian persentase lebar kolom biar teks wrap (turun ke bawah) dengan rapi */ @endphp
                                <th class="ps-4" style="width: 12%;">TGL & INVOICE</th>
                                <th style="width: 15%;">PEMBELI</th>
                                <th style="width: 25%;">ALAMAT PENGIRIMAN</th>
                                <th style="width: 23%;">ITEM PESANAN</th>
                                <th style="width: 10%;">TOTAL HARGA</th>
                                <th style="width: 5%;">STATUS</th>
                                <th class="text-end pe-4" style="width: 10%;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            
                            @php /* Melakukan iterasi perulangan untuk merender data transaksi dari basis data MongoDB */ @endphp
                            @forelse($transaksi as $trx)
                            <tr class="border-bottom">
                                
                                <td class="ps-4">
                                    @php /* Memformat data tanggal menggunakan pustaka Carbon bawaan Laravel */ @endphp
                                    <span class="d-block small text-secondary mb-1">{{ $trx->created_at ? \Carbon\Carbon::parse($trx->created_at)->format('d M Y - H:i') : '-' }}</span>
                                    <span class="fw-bold text-dark text-break">{{ $trx->invoice }}</span>
                                </td>
                                
                                <td>
                                    <span class="d-block fw-bold text-dark mb-1">{{ $trx->nama }}</span>
                                    <span class="small text-secondary"><i class="bi bi-telephone-fill me-1"></i>{{ $trx->no_hp }}</span>
                                </td>
                                
                                <td>
                                    <span class="d-block text-dark mb-1 lh-base">{{ $trx->alamat }}</span>
                                    <span class="small text-secondary fw-semibold">{{ $trx->kota }}, {{ $trx->kode_pos }}</span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        @php /* Mengekstrak array item dari setiap transaksi untuk dirender secara individual */ @endphp
                                        @forelse(is_iterable(data_get($trx, 'items')) ? data_get($trx, 'items') : [] as $item)
                                        <div class="d-flex align-items-start gap-3 bg-light border p-2 rounded-3">
                                            <img src="{{ asset('img/pakaian/' . (data_get($item, 'gambar') ?? 'default.jpg')) }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-2 shadow-sm" alt="Item">
                                            <div class="small lh-sm w-100">
                                                <span class="d-block fw-bold text-dark mb-1 lh-base">{{ data_get($item, 'nama_pakaian') }}</span>
                                                <span class="badge bg-secondary">Qty: {{ data_get($item, 'quantity') }}</span>
                                            </div>
                                        </div>
                                        @empty
                                        <span class="text-muted small fst-italic">Detail item tidak ditemukan</span>
                                        @endforelse
                                    </div>
                                </td>
                                
                                <td>
                                    @php /* Memformat kalkulasi total harga menjadi standar mata uang IDR */ @endphp
                                    <span class="fw-bold text-success fs-6 text-nowrap">Rp {{ number_format((float)($trx->total_harga ?? 0), 0, ',', '.') }}</span>
                                </td>
                                
                                <td>
                                    @php
                                        /* Logika kondisional penentuan warna antarmuka lencana berdasarkan status operasional pesanan */
                                        $badgeColor = 'bg-warning text-dark';
                                        if($trx->status == 'DIPROSES') $badgeColor = 'bg-primary';
                                        if($trx->status == 'DIKIRIM') $badgeColor = 'bg-info text-dark';
                                        if($trx->status == 'SELESAI') $badgeColor = 'bg-success';
                                        if($trx->status == 'BATAL') $badgeColor = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $badgeColor }} fw-bold px-3 py-2 rounded-pill shadow-sm">{{ $trx->status }}</span>
                                </td>
                                
                                <td class="text-end pe-4">
                                    @php /* Membuka form transmisi data untuk memodifikasi status pesanan di basis data */ @endphp
                                    <form action="{{ url('/admin/transaksi/'.$trx->_id.'/status') }}" method="POST" class="d-flex flex-column gap-2 align-items-end">
                                        
                                        @php /* Generasi token perlindungan CSRF untuk validasi keamanan pengiriman formulir */ @endphp
                                        @csrf
                                        <select name="status" class="form-select form-select-sm shadow-none fw-semibold" style="width: 110px;" {{ in_array($trx->status, ['SELESAI', 'BATAL']) ? 'disabled' : '' }}>
                                            <option value="PENDING" {{ $trx->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                            <option value="DIPROSES" {{ $trx->status == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                                            <option value="DIKIRIM" {{ $trx->status == 'DIKIRIM' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="SELESAI" {{ $trx->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                                            <option value="BATAL" {{ $trx->status == 'BATAL' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-dark btn-sm fw-bold w-100 shadow-sm" style="max-width: 110px;" {{ in_array($trx->status, ['SELESAI', 'BATAL']) ? 'disabled' : '' }}>UPDATE</button>
                                    </form>
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox-fill display-4 d-block mb-3 opacity-50"></i>
                                    <span class="fw-bold">Belum ada pesanan masuk bro.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>