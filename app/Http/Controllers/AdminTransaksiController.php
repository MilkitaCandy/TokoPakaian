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

    // Fitur buat ngubah status pesanan
    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        if($transaksi) {
            Transaksi::where('_id', $id)->update([
                'status' => $request->status
            ]);
            return back()->with('success', 'Status pesanan ' . $transaksi->invoice . ' berhasil diubah menjadi ' . $request->status);
        }
        return back()->with('error', 'Pesanan tidak ditemukan!');
    }
}