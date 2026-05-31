<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pakaian;

class PakaianController extends Controller {
    
    // Page 1: Landing Page Customer
    public function home() {
        $dataPakaian = Pakaian::orderBy('_id', 'desc')->take(4)->get();
        return view('customer.home', compact('dataPakaian'));
    }

    // Page 2: Full Katalog Customer
    public function katalog(Request $request)
    {
        $query = Pakaian::query();

        // Fitur Search Nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_pakaian', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // 3. Fitur Sort
        $sort = $request->query('sort', 'terbaru');
        if ($sort == 'termurah') {
            $query->orderBy('harga', 'asc');
        } elseif ($sort == 'termahal') {
            $query->orderBy('harga', 'desc');
        } else {
            $query->orderBy('_id', 'desc');
        }

        $dataPakaian = $query->paginate(8);
        return view('customer.katalog', compact('dataPakaian', 'sort'));
    }

    // Dashboard Admin
    public function adminIndex() {
        $dataPakaian = Pakaian::orderBy('_id', 'desc')->paginate(4);
        return view('admin.dashboard', compact('dataPakaian'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_pakaian' => 'required', 'merk' => 'required', 'kategori' => 'required',
            'tahun_rilis' => 'required|numeric', 'stok' => 'required|numeric', 'harga' => 'required|numeric',
            'gambar' => 'required|image|max:2048', 
        ]);
        $nama_file = time() . '_' . $request->file('gambar')->getClientOriginalName();
        $request->file('gambar')->move(public_path('img/pakaian'), $nama_file);
        // Simpan data produk ke MongoDB
        Pakaian::create([
            'nama_pakaian' => $request->nama_pakaian, 'merk' => $request->merk, 'kategori' => $request->kategori,
            'tahun_rilis' => (int)$request->tahun_rilis, 'stok' => (int)$request->stok, 'harga' => (int)$request->harga,
            'gambar' => $nama_file,
        ]);
        return redirect('/dashboard-admin')->with('success', 'Produk sukses ditambah!');
    }
    // Fitur Hapus Produk di Dashboard Admin
    public function destroy($id) {
        $pakaian = Pakaian::findOrFail($id);
        if (file_exists(public_path('img/pakaian/' . $pakaian->gambar))) {
            unlink(public_path('img/pakaian/' . $pakaian->gambar));
        }
        $pakaian->delete();
        return redirect('/dashboard-admin')->with('success', 'Produk sukses dihapus!');
    }
    // Fitur Update Produk di Dashboard Admin
    public function update(Request $request, $id) {
        $pakaian = Pakaian::findOrFail($id);

        // Validasi input gambar 
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

        // Cek kalau admin upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari folder
            if (file_exists(public_path('img/pakaian/' . $pakaian->gambar))) {
                unlink(public_path('img/pakaian/' . $pakaian->gambar));
            }
            // Simpan gambar baru
            $nama_file = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('img/pakaian'), $nama_file);
            // Tambahkan nama file baru ke data yang mau diupdate
            $dataUpdate['gambar'] = $nama_file;
        }

        // Eksekusi update ke database MongoDB
        $pakaian->update($dataUpdate);
        return redirect('/dashboard-admin')->with('success', 'Data produk sukses diupdate!');
    }
}