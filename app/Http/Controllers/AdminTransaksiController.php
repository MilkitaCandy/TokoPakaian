<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class AdminTransaksiController extends Controller
{
    // menampilkan halaman manajemen pesanan
    public function index()
    {
        // Urutkan dari pesanan terbaru ke terlama
        $transaksi = Transaksi::orderBy('created_at', 'desc')->get();
        return view('admin.transaksi', compact('transaksi'));
    }

    // Fitur untuk mengubah status pesanan
    public function updateStatus(Request $request, $id)
{
    $transaksi = \App\Models\Transaksi::findOrFail($id);

    // 1. Logika pengembalian stok jika status diubah menjadi BATAL
    if ($request->status == 'BATAL' && $transaksi->status != 'BATAL') {
        foreach ($transaksi->items as $item) {
            $baju = \App\Models\Pakaian::find($item['pakaian_id']);
            if ($baju) {
                // Kembalikan stok sesuai jumlah yang dibeli
                $baju->increment('stok', $item['quantity']);
            }
        }
    }

    // 2. (Opsional) Kalau admin salah klik BATAL, lalu diubah lagi ke PENDING/DIPROSES
    // Maka stoknya harus dikurangin lagi biar nggak dobel
    if ($transaksi->status == 'BATAL' && $request->status != 'BATAL') {
        foreach ($transaksi->items as $item) {
            $baju = \App\Models\Pakaian::find($item['pakaian_id']);
            if ($baju) {
                // Potong stoknya lagi
                $baju->decrement('stok', $item['quantity']);
            }
        }
    }

    // 3. Update status transaksinya
    $transaksi->update(['status' => $request->status]);

    return back()->with('success', 'Status transaksi berhasil diupdate dan stok sudah disesuaikan!');
}

}