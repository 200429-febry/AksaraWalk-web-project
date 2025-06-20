<?php
namespace App\Http\Controllers;
use App\Models\Pelanggan; // Impor model Pelanggan
class DashboardController extends Controller {
    public function index() {
        $pelanggan = Pelanggan::orderBy('tanggal_registrasi', 'desc')->get();
        return view('dashboard', ['list_pelanggan' => $pelanggan]);
    }
}