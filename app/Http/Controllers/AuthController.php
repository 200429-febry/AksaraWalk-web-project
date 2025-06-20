<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman sign-in dan sign-up 3D.
     *
     * @return \Illuminate\View\View
     */
    public function discover()
    {
        // Method ini memuat view 'discover.blade.php'.
        // Anda perlu memindahkan konten dari 'discover/index.html'
        // ke 'resources/views/discover.blade.php' dan menyesuaikan
        // path untuk file CSS dan JS menggunakan helper asset().
        // Contoh: <link rel="stylesheet" href="{{ asset('discover/style.css') }}">
        return view('discover');
    }

    /**
     * Di sini Anda nantinya akan menambahkan fungsi untuk memproses login
     * public function handleLogin(Request $request) { ... }
     */

    /**
     * Dan fungsi untuk memproses registrasi
     * public function handleRegister(Request $request) { ... }
     */
}