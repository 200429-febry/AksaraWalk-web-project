<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreOrderController extends Controller
{
    /**
     * Menampilkan halaman formulir registrasi pre-order.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Method ini akan memuat view 'register.blade.php'
        // yang berisi formulir HTML untuk diisi pengguna.
        return view('register');
    }

    /**
     * Menyimpan data pre-order yang dikirim dari formulir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // --- LANGKAH 1: Validasi Input ---
        // Ini adalah langkah keamanan penting untuk memastikan data yang masuk
        // sesuai dengan format yang kita inginkan.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pre_orders,email', // 'unique:pre_orders,email' akan memastikan tidak ada email ganda yang mendaftar.
            'phone' => 'required|string|min:10|max:15',
        ]);

        // --- LANGKAH 2: Simpan ke Database (Langkah Selanjutnya) ---
        // Kode untuk menyimpan $validatedData ke dalam database akan ada di sini.
        // Contoh:
        // PreOrder::create($validatedData);

        // --- LANGKAH 3: Redirect Pengguna ---
        // Setelah berhasil, arahkan pengguna kembali ke halaman utama
        // dengan pesan sukses. Pesan ini dapat ditampilkan di halaman utama.
        return redirect()->route('home')->with('success', 'Terima kasih! Pre-order Anda telah berhasil dicatat.');
    }
}