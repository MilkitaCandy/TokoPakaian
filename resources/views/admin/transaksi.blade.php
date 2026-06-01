<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pesanan Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>body { background-color: #f4f6f9; }</style>
</head>
<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Manajemen Pesanan</h3>
           <a href="{{ url('/dashboard-admin') }}" class="btn btn-outline-dark fw-bold">Kembali ke Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success fw-bold">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3 px-4">TGL & INVOICE</th>
                                <th>PEMBELI</th>
                                <th>TOTAL HARGA</th>
                                <th>STATUS</th>
                                <th class="text-end px-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $trx)
                            <tr>
                                <td class="px-4">
                                    <span class="d-block small text-muted">{{ $trx->created_at->format('d M Y - H:i') }}</span>
                                    <span class="fw-bold">{{ $trx->invoice }}</span>
                                </td>
                                <td>
                                    <span class="d-block fw-bold">{{ $trx->nama }}</span>
                                    <span class="small text-muted">{{ $trx->kota }} ({{ $trx->no_hp }})</span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $badgeColor = 'bg-warning text-dark'; // Default Pending
                                        if($trx->status == 'DIPROSES') $badgeColor = 'bg-primary';
                                        if($trx->status == 'DIKIRIM') $badgeColor = 'bg-info text-dark';
                                        if($trx->status == 'SELESAI') $badgeColor = 'bg-success';
                                        if($trx->status == 'BATAL') $badgeColor = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $badgeColor }} fw-bold px-3 py-2 rounded-pill">{{ $trx->status }}</span>
                                </td>
                                <td class="text-end px-4">
                                    <form action="{{ url('/admin/transaksi/'.$trx->_id.'/status') }}" method="POST" class="d-flex gap-2 justify-content-end">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm w-auto shadow-none">
                                            <option value="PENDING" {{ $trx->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                            <option value="DIPROSES" {{ $trx->status == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                                            <option value="DIKIRIM" {{ $trx->status == 'DIKIRIM' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="SELESAI" {{ $trx->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                                            <option value="BATAL" {{ $trx->status == 'BATAL' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-dark btn-sm fw-bold">UPDATE</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted fw-bold">Belum ada pesanan masuk bro.</td>
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