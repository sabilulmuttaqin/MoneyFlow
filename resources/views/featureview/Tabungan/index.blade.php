@extends('layouts.nav')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/Tabungan.css') }}">
@endsection

@section('content')

{{-- ALERT --}}
@if(session('success'))
<div class="alert success">
    <span class="alert-icon">✔</span>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert error">
    <span class="alert-icon">⚠</span>
    {{ session('error') }}
</div>
@endif

<div class="page-wrapper">

    {{-- HEADER --}}
    <div class="header-section">
        <h2>Tujuan Tabungan</h2>
        <button class="btn-tambah" onclick="openModal()">+ Tambah Tujuan</button>
    </div>

    {{-- LIST TABUNGAN --}}
    <div class="tabungan-grid">
        @foreach ($tabungan as $item)
            @php
                $totalSekarang = ($item->setoran_awal ?? 0) + $item->total_setoran;
                $progress = $item->target > 0
                    ? min(($totalSekarang / $item->target) * 100, 100)
                    : 0;
            @endphp

            {{-- CARD --}}
            <div class="card-tabungan">

                {{-- ICON AKSI --}}
                <div class="aksi-icons">
                    <button
                        type="button"
                        onclick="event.stopPropagation(); openEditModal(
                            '{{ $item->id }}',
                            '{{ $item->nama }}',
                            '{{ $item->target }}',
                            '{{ $item->setoran_awal }}'
                        )">
                        ✏️
                    </button>

                    <button
                        type="button"
                        onclick="event.stopPropagation(); openDeleteModal(
                            '{{ $item->id }}',
                            '{{ $item->nama }}'
                        )">
                        🗑️
                    </button>
                </div>

                {{-- LINK KE DETAIL --}}
                <a href="{{ route('tabungan.show', $item->id) }}" class="card-link">

                    <div class="card-header">
                        <div class="text-section">
                            <h4>{{ $item->nama }}</h4>
                            <p class="target">
                                Target: Rp {{ number_format($item->target, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <p class="sekang">
                        Tabungan sekarang:
                        Rp {{ number_format($totalSekarang, 0, ',', '.') }}
                    </p>

                    <div class="progress-section">
                        <div
                            class="progress-bar"
                            style="width: {{ $progress }}%">
                        </div>
                    </div>

                    <span class="progress-label">{{ round($progress) }}%</span>

                </a>
            </div>
        @endforeach
    </div>

    {{-- MODAL --}}
    @include('featureview.Tabungan.modal-create')
    @include('featureview.Tabungan.modal-edit')
    @include('featureview.Tabungan.modal-delate')

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/tabungan.js') }}"></script>
@endsection
