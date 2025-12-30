@extends('layouts.nav')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/TabunganDetail.css') }}">
@endsection

@section('content')
<div class="page-wrapper">

    <!-- Header -->
    <div class="detail-header">
        <a href="{{ route('tabungan.index') }}" class="btn-back">←</a>
        <h2>{{ $tabungan->nama }}</h2>
    </div>

    <div class="detail-container">

        <!-- Progress Circle -->
        @php
            $progress = $tabungan->progress;
        @endphp

        <div class="progress-card">
            <div class="progress-circle" style="--value: {{ $progress }}%">
                <div class="inner">
                    <h3>{{ round($progress) }}%</h3>
                    <p>Rp {{ number_format($tabungan->total_terkumpul, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="info-box">
                <p><b>Target:</b> Rp {{ number_format($tabungan->target, 0, ',', '.') }}</p>
                @if($tabungan->tenggat)
                <p><b>Tenggat:</b> {{ \Carbon\Carbon::parse($tabungan->tenggat)->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Riwayat Setoran -->
        <div class="riwayat-card">
            <h4>Riwayat Setoran</h4>

            @forelse ($setoran as $s)
            <div class="riwayat-item">
                <span>{{ \Carbon\Carbon::parse($s->created_at)->format('d M') }}</span>
                <span>Rp {{ number_format($s->jumlah, 0, ',', '.') }}</span>
            </div>
            @empty
                <p class="empty">Belum ada setoran</p>
            @endforelse


            <button class="btn-primary" onclick="openSetoranModal()">Tambah Setoran</button>
        </div>

    </div>

</div>

@include('featureview.Tabungan.modal-setoran')
@include('featureview.Tabungan.modal-success')

@endsection

@section('scripts')
<script src="{{ asset('js/tabungan-detail.js') }}"></script>
@endsection
