<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pakaian;

class PakaianController extends Controller {
    
    // READ BAGIAN CUSTOMER (MENAMPILKAN DATA KE PENGGUNA)

    // Page 1: Landing Page Customer
    public function home() {
        // READ: Mengambil 4 data pakaian terbaru berdasarkan _id (MongoDB)
        $dataPakaian = Pakaian::orderBy('_id', 'desc')->take(4)->get();
        return view('customer.home', compact('dataPakaian'));
    }

    // Page 2: Full Katalog Customer
    public function katalog(Request $request)
    {
        // READ Menyiapkan query untuk mengambil data pakaian
        $query = Pakaian::query();

        // Fitur Search Nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_pakaian', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Fitur Sort
        $sort = $request->query('sort', 'terbaru');
        if ($sort == 'termurah') {
            $query->orderBy('harga', 'asc');
        } elseif ($sort == 'termahal') {
            $query->orderBy('harga', 'desc');
        } else {
            $query->orderBy('_id', 'desc');
        }

        // READ: Mengeksekusi query dengan pagination (8 item per halaman)
        $dataPakaian = $query->paginate(8);
        return view('customer.katalog', compact('dataPakaian', 'sort'));
    }



    // CRUD 

    // 1. READ Menampilkan daftar produk di Dashboard Admin
    public function adminIndex() {
        // READ: Mengambil semua data pakaian untuk ditampilkan di tabel admin
        $dataPakaian = Pakaian::orderBy('_id', 'desc')->paginate(4);
        return view('admin.dashboard', compact('dataPakaian'));
    }

    // 2. CREATE Menambahkan produk baru ke database
    public function store(Request $request) {
        // Validasi input dari form
        $request->validate([
            'nama_pakaian' => 'required', 'merk' => 'required', 'kategori' => 'required',
            'tahun_rilis' => 'required|numeric', 'stok' => 'required|numeric', 'harga' => 'required|numeric',
            'gambar' => 'required|image|max:2048', 
        ]);

        // Proses upload gambar
        $nama_file = time() . '_' . $request->file('gambar')->getClientOriginalName();
        $request->file('gambar')->move(public_path('img/pakaian'), $nama_file);
        
        // CREATE: Menyimpan data produk baru ke MongoDB
        Pakaian::create([
            'nama_pakaian' => $request->nama_pakaian, 'merk' => $request->merk, 'kategori' => $request->kategori,
            'tahun_rilis' => (int)$request->tahun_rilis, 'stok' => (int)$request->stok, 'harga' => (int)$request->harga,
            'gambar' => $nama_file,
        ]);

        return redirect('/dashboard-admin')->with('success', 'Produk sukses ditambah!');
    }

    // 3. DELETE Menghapus produk dari database
    public function destroy($id) {
        // READ & DELETE: Mencari data berdasarkan ID, lalu menghapusnya
        $pakaian = Pakaian::findOrFail($id);
        
        // Hapus file gambar fisik dari folder public/img/pakaian
        if (file_exists(public_path('img/pakaian/' . $pakaian->gambar))) {
            unlink(public_path('img/pakaian/' . $pakaian->gambar));
        }
        
        // DELETE: Eksekusi hapus data dari database
        $pakaian->delete();
        
        return redirect('/dashboard-admin')->with('success', 'Produk sukses dihapus!');
    }

    // 4. UPDATE Mengubah atau memperbarui data produk yang sudah ada
    public function update(Request $request, $id) {
        // READ: Mencari data spesifik yang akan diupdate
        $pakaian = Pakaian::findOrFail($id);

        // Validasi input form update 
        $request->validate([
            'nama_pakaian' => 'required', 'merk' => 'required', 'kategori' => 'required',
            'tahun_rilis' => 'required|numeric', 'stok' => 'required|numeric', 'harga' => 'required|numeric',
            'gambar' => 'nullable|image|max:2048', 
        ]);

        // Siapkan data teks yang mau diupdate
        $dataUpdate = [
            'nama_pakaian' => $request->nama_pakaian, 'merk' => $request->merk, 'kategori' => $request->kategori,
            'tahun_rilis' => (int)$request->tahun_rilis, 'stok' => (int)$request->stok, 'harga' => (int)$request->harga,
        ];

        // Cek kalau admin upload gambar baru untuk mengganti gambar lama
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari folder
            if (file_exists(public_path('img/pakaian/' . $pakaian->gambar))) {
                unlink(public_path('img/pakaian/' . $pakaian->gambar));
            }
            // Simpan gambar baru
            $nama_file = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('img/pakaian'), $nama_file);
            
            // Tambahkan nama file baru ke array data yang mau diupdate
            $dataUpdate['gambar'] = $nama_file;
        }

        // UPDATE Eksekusi pembaruan data ke database MongoDB
        $pakaian->update($dataUpdate);
        
        return redirect('/dashboard-admin')->with('success', 'Data produk sukses diupdate!');
    }
}