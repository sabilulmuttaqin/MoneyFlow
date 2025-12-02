@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Category.css') }}">
<div class="container mt-4">
    <h3 class="mb-4">Tambah Kategori Baru</h3>

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf

        {{-- Nama Kategori --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama Kategori</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Makanan, Transport" required>
        </div>

        {{-- Jenis Kategori --}}
        <div class="mb-3">
            <label class="form-label d-block">Jenis Kategori</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="type-expense" value="pengeluaran" checked>
                <label class="form-check-label" for="type-expense">Pengeluaran</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="type-income" value="pemasukan">
                <label class="form-check-label" for="type-income">Pemasukan</label>
            </div>
        </div>

        {{-- Jumlah / Budget --}}
        <div class="mb-3">
            <label for="budget" class="form-label">Jumlah Anggaran (Rp)</label>
            <input type="number" id="budget" name="budget" class="form-control" placeholder="Masukkan jumlah" required min="0">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="kebutuhan pokok">Kebutuhan Pokok</option>
                <option value="keinginan">Keinginan</option>
                <option value="tabungan">Tabungan</option>
            </select>
        </div>

        {{-- Icon --}}
        <div class="mb-3">
            <label for="icon" class="form-label">Icon (Bootstrap Icon)</label>
            <input type="text" id="icon" name="icon" class="form-control" placeholder="Contoh: cart, airplane, bus">
            <small class="text-muted">Gunakan nama ikon dari <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
