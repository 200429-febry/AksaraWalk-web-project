@extends('layouts.app')

@section('content')
<main class="container" style="padding-top: 120px; padding-bottom: 60px; color: white;">
    <div class="section-heading">
        <h2 class="heading-two">Admin <span>Dashboard</span></h2>
        <p class="sub-heading">Daftar Pelanggan</p>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: rgba(255,255,255,0.1);">
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Telepon</th>
                    <th style="padding: 12px;">Tanggal Registrasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list_pelanggan as $pelanggan)
                    <tr>
                        <td style='padding: 12px; border-top: 1px solid #444;'>{{ $pelanggan->nama }}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>{{ $pelanggan->email }}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>{{ $pelanggan->telepon }}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>{{ $pelanggan->tanggal_registrasi->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding: 12px; text-align: center;">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection