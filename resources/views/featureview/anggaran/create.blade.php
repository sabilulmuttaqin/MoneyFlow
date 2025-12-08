@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Category.css') }}">
<div class="container mt-4">
    <h3 class="mb-4">Tambah Anggaran Baru</h3>

    <form action="{{ route('anggaran.store') }}" method="POST">
        @csrf

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" id="kategori" name="kategori" class="form-control" placeholder="Contoh: Makanan, Transport" required>
        </div>

        {{-- Prosentase --}}
        <div class="mb-3">
            <label for="prosentase" class="form-label">Prosentase (%)</label>
            <input type="number" id="prosentase" name="prosentase" class="form-control" placeholder="Masukkan prosentase" min="0" max="100" required>
        </div>

        {{-- Nominal --}}
        <div class="mb-3">
            <label for="nominal" class="form-label">Nominal (Rp)</label>
            <input type="number" id="nominal" name="nominal" class="form-control" placeholder="Masukkan nominal" min="0" required>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('anggaran.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
