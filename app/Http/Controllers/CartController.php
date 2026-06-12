<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pakaian;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    // Fungsi untuk menambahkan barang ke keranjang
    public function add(Request $request)
    {
        $pakaian = Pakaian::find($request->pakaian_id);
        if(!$pakaian) {
            return back()->with('error', 'Barang tidak ditemukan!');
        }
        
        // Cek stok, jika stok 0 dilarang masuk keranjang
        if($pakaian->stok <= 0) {
            return back()->with('error', 'Mohon maaf, stok ' . $pakaian->nama_pakaian . ' sedang kosong.');
        }
        $cart = session()->get('cart', []);

        // Jika barang sudah ada di keranjang, tambahkan jumlahnya
        if(isset($cart[$request->pakaian_id])) {
            
            // Periksa apakah penambahan ke keranjang melebihi sisa stok di database
            if($cart[$request->pakaian_id]['quantity'] >= $pakaian->stok) {
                return back()->with('error', 'Anda tidak dapat membeli melebihi sisa stok yang tersedia. Sisa stok: ' . $pakaian->stok);
            }
            $cart[$request->pakaian_id]['quantity']++;
        } else {
            $cart[$request->pakaian_id] = [
                "nama_pakaian" => $pakaian->nama_pakaian,
                "gambar" => $pakaian->gambar,
                "harga" => $pakaian->harga,
                "quantity" => 1
            ];
        }
        session()->put('cart', $cart);   
        return back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Fungsi untuk menghapus barang dari keranjang
    public function remove(Request $request)
    {
        if($request->pakaian_id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->pakaian_id])) {
                unset($cart[$request->pakaian_id]);
                session()->put('cart', $cart);
            }
            return back();
        }
    }

    // Fungsi untuk menampilkan halaman checkout
    public function checkout()
    {
        // Jika keranjang kosong namun pengguna mengakses URL /checkout, arahkan kembali ke katalog
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect('/katalog')->with('error', 'Keranjang Anda masih kosong!');
        }

        return view('customer.checkout');
    }

    // Fungsi untuk proses checkout, simpan data ke MongoDB Atlas & Midtrans
    public function process(Request $request)
    {
        $cart = session()->get('cart');
        
        if(!$cart || count($cart) == 0) {
            return redirect('/katalog')->with('error', 'Keranjang kosong!');
        }
        
        foreach($cart as $id => $details) {
            $baju = Pakaian::find($id);
            if(!$baju || $baju->stok < $details['quantity']) {
                return redirect('/katalog')->with('error', 'Mohon maaf, stok ' . $details['nama_pakaian'] . ' sudah habis atau tidak mencukupi. Silakan periksa kembali keranjang Anda.');
            }
        }
        
        $total = 0;
        $items = []; 
        foreach($cart as $id => $details) {
            $total += $details['harga'] * $details['quantity'];
            
            $items[] = [
                'pakaian_id' => $id,
                'nama_pakaian' => $details['nama_pakaian'],
                'harga' => (int)$details['harga'],
                'quantity' => (int)$details['quantity'],
                'gambar' => $details['gambar'] ?? 'default.jpg'
            ];

            // Kurangi stok pakaian
            $baju = Pakaian::find($id);
            $baju->decrement('stok', $details['quantity']);
        }
        
        // Buat nomor Invoice unik
        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Simpan transaksi ke database
        $transaksi = Transaksi::create([
            'invoice' => $invoice,
            'username' => auth()->user()->username, 
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'kode_pos' => $request->kode_pos,
            'items' => $items, 
            'total_harga' => $total,
            'status' => 'PENDING', 
        ]);

        // LOGIKA MIDTRANS SNAP (Generate Token)
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [] // <-- Penanganan untuk mencegah error 10023
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $invoice, // Sinkronisasi dengan invoice database
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $request->nama,
                'email' => $request->email,
                'phone' => $request->no_hp,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Kosongkan keranjang setelah checkout
        session()->forget('cart');

        // Arahkan ke halaman pembayaran beserta token
        return view('customer.bayar', compact('snapToken', 'transaksi'));
    }

    // Fungsi untuk menampilkan halaman riwayat pesanan customer
    public function riwayat()
    {
        if(!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }
        $riwayat = Transaksi::where('username', auth()->user()->username)
                          ->orderBy('created_at', 'desc')
                          ->get();
        return view('customer.riwayat', compact('riwayat'));
    }

    // Fungsi untuk melanjutkan pembayaran jika belum selesai
    public function bayarLagi($invoice)
    {
        // Cari transaksi tanpa memfilter status PENDING terlebih dahulu
        $transaksi = Transaksi::where('invoice', $invoice)
                              ->where('username', auth()->user()->username)
                              ->first();

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return redirect('/riwayat')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Jika pengguna mencoba membayar pesanan yang sudah berstatus LUNAS / DIPROSES
        if (in_array($transaksi->status, ['DIPROSES', 'DIKIRIM', 'SELESAI'])) {
            return redirect('/riwayat')->with('success', 'Pesanan Anda ini sudah lunas dibayar.');
        }

        // Jika statusnya sudah BATAL
        if ($transaksi->status == 'BATAL') {
            return redirect('/riwayat')->with('error', 'Pesanan sudah dibatalkan dan tidak dapat dibayar lagi.');
        }

        // Jika statusnya masih PENDING, siapkan token
        $snapToken = $transaksi->snap_token;

        // Antisipasi jika transaksi lama belum memiliki snap_token
        if (!$snapToken) {
            return redirect('/riwayat')->with('error', 'Anda sudah membayar, mohon tunggu pesanan diproses.');
        }

        // Arahkan langsung ke halaman pembayaran (bayar.blade.php)
        return view('customer.bayar', compact('snapToken', 'transaksi'));
    }
}