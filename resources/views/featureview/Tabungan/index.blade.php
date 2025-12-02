@extends('layouts.nav')

@section('content')

<div class="header-section">
    <h2>Tujuan Tabungan</h2>
    <button class="btn-tambah" onclick="openModal()">+ Tambah Tujuan</button>
</div>

<div class="tabungan-grid">
    @foreach($tabungan as $item)
        @php
            $totalSekarang = ($item->setoran_awal ?? 0) + $item->total_setoran;
            $progress = ($item->target > 0)
                ? min(($totalSekarang / $item->target) * 100, 100)
                : 0;
        @endphp

        <div class="card-tabungan">
    <div class="card-header">
        <div class="text-section">
            <h4>{{ $item->nama }}</h4>
            <p class="target">Target: Rp {{ number_format($item->target, 0, ',', '.') }}</p>
        </div>

        <div class="aksi-icons">
            <a href="{{ route('tabungan.show', $item->id) }}" class="btn-edit">
                ✏️
            </a>

            <form action="#" method="POST" onsubmit="return confirm('Hapus?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete">
                    🗑️
                </button>
            </form>
        </div>
    </div>

    <p class="sekang">Tabungan sekarang: Rp {{ number_format($totalSekarang, 0, ',', '.') }}</p>

    <div class="progress-section">
        <div class="progress-bar" style="width: {{ $progress }}%"></div>
    </div>
    <span class="progress-label">{{ round($progress) }}%</span>
</div>

    @endforeach
</div>

@include('featureview.Tabungan.modal-create')

@endsection


@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Tabungan.css') }}">
@endsection