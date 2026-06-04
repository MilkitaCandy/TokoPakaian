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
            return back()->with('error', 'Barang ghaib!');
        }
        
        // Cek stok, jika stok 0 dilarang masuk keranjang
        if($pakaian->stok <= 0) {
            return back()->with('error', 'Yah, stok ' . $pakaian->nama_pakaian . ' lagi kosong bro!');
        }
        $cart = session()->get('cart', []);

        // jika barang ada di keranjang, menambahkan jumlahnya
        if(isset($cart[$request->pakaian_id])) {
            
            // Cek apakah jika ditambahin ke keranjang bakal melebihi sisa stok di DB?
            if($cart[$request->pakaian_id]['quantity'] >= $pakaian->stok) {
                return back()->with('error', 'Lu gak bisa beli lebih dari sisa stok bro! Sisa cuma ' . $pakaian->stok);
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
        return back()->with('success', 'Berhasil masuk keranjang!');
    }

    // Fungsi untuk membuang barang dari keranjang
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
        // Kalau keranjang kosong tapi user iseng maksa masuk URL /checkout, tendang balik ke katalog
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect('/katalog')->with('error', 'Keranjang masih kosong!');
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
                return redirect('/katalog')->with('error', 'Sorry bro, stok ' . $details['nama_pakaian'] . ' udah keduluan abis/gak cukup. Cek keranjang lu lagi ya!');
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

            // potong stok
            $baju = Pakaian::find($id);
            $baju->decrement('stok', $details['quantity']);
        }
        
        // Bikin Invoice unik
        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Simpan transaksi ke MongoDB
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

        // ==========================================
        // LOGIKA MIDTRANS SNAP (Generate Token)
        // ==========================================
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [] // <-- Ini bumbu rahasia penangkal error 10023
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $invoice, // Sinkron sama invoice database
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $request->nama,
                'email' => $request->email,
                'phone' => $request->no_hp,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Kosongin keranjang
        session()->forget('cart');

        // Lempar ke halaman bayar dengan bawa tokennya
        return view('customer.bayar', compact('snapToken', 'transaksi'));
    }

    // Fungsi untuk menampilkan halaman riwayat pesanan customer
    public function riwayat()
    {
        if(!auth()->check()) {
            return redirect('/login')->with('error', 'Login dulu bro!');
        }
        $riwayat = Transaksi::where('username', auth()->user()->username)
                          ->orderBy('created_at', 'desc')
                          ->get();
        return view('customer.riwayat', compact('riwayat'));
    }

    // Fungsi untuk pembayaran jika belum selesai
    public function bayarLagi($invoice)
    {
        // Cari transaksi tanpa ngefilter status PENDING dulu
        $transaksi = Transaksi::where('invoice', $invoice)
                              ->where('username', auth()->user()->username)
                              ->first();

        // Kalau transaksinya beneran gaada (user iseng ngasal ketik URL)
        if (!$transaksi) {
            return redirect('/riwayat')->with('error', 'Transaksi tidak ditemukan bro.');
        }

        // Kalau iseng pencet bayar padahal status udah LUNAS / DIPROSES
        if (in_array($transaksi->status, ['DIPROSES', 'DIKIRIM', 'SELESAI'])) {
            return redirect('/riwayat')->with('success', 'Santai bro, pesanan lu yang ini udah lunas dibayar kok!');
        }

        // Kalau statusnya udah BATAL
        if ($transaksi->status == 'BATAL') {
            return redirect('/riwayat')->with('error', 'Orderan lu udah dibatalin, gak bisa dibayar lagi bro.');
        }

        // Nah, kalau statusnya emang masih PENDING, baru kita siapin tokennya
        $snapToken = $transaksi->snap_token;

        // Jaga-jaga kalau transaksi lama belum ada snap_token-nya
        if (!$snapToken) {
            return redirect('/riwayat')->with('error', 'kamu Udah bayar tunggu di proses ya!');
        }

        // Arahin langsung ke halaman hitam (bayar.blade.php)
        return view('customer.bayar', compact('snapToken', 'transaksi'));
    }
}