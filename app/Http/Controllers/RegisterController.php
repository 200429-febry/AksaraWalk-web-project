<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pelanggan; // Impor model Pelanggan
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller {
    public function show() { return view('register'); }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggan,email',
            'phone' => 'required|string|min:10',
        ]);

        Pelanggan::create([
            'nama' => $validatedData['name'],
            'email' => $validatedData['email'],
            'telepon' => $validatedData['phone'],
        ]);

        return redirect()->route('home')->with('success', 'Registrasi Berhasil!');
    }
}