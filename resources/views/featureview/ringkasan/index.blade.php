@extends('layouts.nav')

@section('content')
<style>
    :root {
        --mf-primary-blue: #2F6BFF;
        --mf-soft-blue: #E3EEFF;
        --mf-soft-bg: #F5F5F7;
        --mf-text-main: #111111;
        --mf-pokok: #7B7DA4;
        --mf-keinginan: #F8B400;
        --mf-tabungan: #00D394;
    }

    .summary-page {
        font-family: "Aspekta", sans-serif;
        background: transparent;
        min-height: calc(100vh - 70px);
        padding: 0;
    }

    .summary-inner {
        width: 100%;
        max-width: 980px;
    }

    .summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .summary-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    /* ===== CUSTOM DROPDOWN BULAN ===== */
    .month-selector {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .month-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-weight: 500;
        font-size: 13px;
        color: #475569;
        cursor: pointer;
    }

    .month-toggle:hover {
        border-color: #cbd5e1;
    }

    .month-toggle-icon {
        font-size: 12px;
        margin-left: 2px;
    }

    .month-list {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        padding: 8px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        display: none;
        max-height: 200px;
        overflow-y: auto;
        min-width: 180px;
        z-index: 50;
    }

    .month-list.show {
        display: block;
    }

    .month-item {
        display: block;
        font-size: 13px;
        padding: 8px 12px;
        text-align: left;
        text-decoration: none;
        color: #475569;
        border-radius: 8px;
        font-weight: 500;
    }

    .month-item:hover {
        background: #f1f5f9;
    }

    .month-item.active {
        color: #5b8def;
        font-weight: 600;
        background: #f1f5f9;
    }
    /* ===== END CUSTOM DROPDOWN ===== */

    .summary-main {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        column-gap: 64px;
        align-items: start;
    }

    .summary-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 32px;
    }

    .summary-right {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 26px;
        padding-top: 20px;
    }

    .donut-wrapper {
        width: 260px;
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .donut-wrapper canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .summary-box {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        width: 100%;
        max-width: 340px;
        border: 1px solid #f1f5f9;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .summary-row .label { color: #64748b; }
    .summary-row .value { font-weight: 600; color: #0f172a; }

    .summary-box hr {
        margin: 12px 0;
        border: none;
        border-top: 1px solid #f1f5f9;
    }

    .legend-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid #f1f5f9;
        width: 100%;
        max-width: 340px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .legend-item:last-child { margin-bottom: 0; }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        display: inline-block;
        flex-shrink: 0;
    }

    .legend-dot.pokok     { background: var(--mf-pokok); }
    .legend-dot.keinginan { background: var(--mf-keinginan); }
    .legend-dot.tabungan  { background: var(--mf-tabungan); }

    .legend-item .percent {
        min-width: 52px;
        font-weight: 700;
    }

    .btn-weekly {
        border-radius: 12px;
        background: #5b8def;
        border: none;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 500;
        color: #ffffff;
        width: 100%;
        max-width: 340px;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-weekly:hover {
        background: #4c7ce5;
    }

    /* ===== MODAL DETAIL MINGGUAN (JANGAN DI DALAM @media) ===== */
    .weekly-modal-overlay{
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 50;
    }

    .weekly-modal-overlay.show{ display: flex; }

    .weekly-modal{
        width: 480px;
        max-width: 100%;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(15,23,42,.12);
        padding: 20px 24px 24px;
        position: relative;
    }

    .weekly-modal-header{
        display:flex;
        align-items:center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .weekly-modal-title{
        font-size: 18px;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
    }

    .weekly-modal-close{
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        color: #64748b;
        display: grid;
        place-items: center;
        border-radius: 8px;
    }
    .weekly-modal-close:hover{ background: #f1f5f9; color: #0f172a; }

    .weekly-modal-grid{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .weekly-pill{
        display:flex;
        align-items:center;
        justify-content:center;
        height: 44px;
        border-radius: 12px;
        background: #5b8def;
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        box-shadow: none;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }
    .weekly-pill:hover{ background: #4c7ce5; }

    @media (max-width: 560px){
        .weekly-modal-title{ font-size: 16px; }
        .weekly-modal-grid{ grid-template-columns: 1fr; }
        .weekly-pill{ height: 40px; font-size: 13px; }
    }

    @media (max-width: 991.98px) {
        .summary-page { padding: 24px 16px 32px 16px; }
        .summary-inner { max-width: 100%; }
        .summary-header { flex-direction: column; align-items: flex-start; gap: 14px; }
        .summary-main { grid-template-columns: 1fr; row-gap: 32px; }
        .summary-right { align-items: flex-start; padding-top: 0; }

        .legend-card, .btn-weekly, .summary-box {
            width: 100%;
            max-width: 100%;
        }
    }
</style>

@php
    $tahunDropdown = date('Y');
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="summary-page">
    <div class="summary-inner">

        <header class="summary-header">
            <h1>Ringkasan Bulan ini</h1>

            {{-- Dropdown bulan custom --}}
            <div class="month-selector">
                <button type="button" class="month-toggle" id="monthToggle">
                    <span>{{ $dataRingkasan['bulan_tahun'] }}</span>
                    <span class="month-toggle-icon">&#9662;</span>
                </button>

                <div class="month-list" id="monthList">
                    @foreach ($availableMonths as $month)
                        <a href="{{ url()->current() }}?bulan={{ $month['value'] }}"
                            class="month-item {{ request('bulan', now()->format('Y-m')) == $month['value'] ? 'active' : '' }}">
                            {{ $month['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <div class="summary-main">
            {{-- Kiri --}}
            <div class="summary-left">
                <div class="donut-wrapper">
                    <canvas id="anggaranDonutChart"></canvas>
                </div>

                <div class="summary-box">

                    <div class="summary-row">
                        <b>Kebutuhan</b><br>
                        Anggaran : Rp {{ number_format($dataRingkasan['anggaran_kebutuhan'], 0, ',', '.') }}<br>
                        Terpakai : Rp {{ number_format($dataRingkasan['terpakai_kebutuhan'], 0, ',', '.') }}
                    </div>

                    <hr>

                    <div class="summary-row">
                        <b>Keinginan</b><br>
                        Anggaran : Rp {{ number_format($dataRingkasan['anggaran_keinginan'], 0, ',', '.') }}<br>
                        Terpakai : Rp {{ number_format($dataRingkasan['terpakai_keinginan'], 0, ',', '.') }}
                    </div>

                    <hr>

                    <div class="summary-row">
                        <b>Tabungan</b><br>
                        Anggaran : Rp {{ number_format($dataRingkasan['anggaran_tabungan'], 0, ',', '.') }}<br>
                        Terpakai : Rp {{ number_format($dataRingkasan['terpakai_tabungan'], 0, ',', '.') }}
                    </div>

                    <hr>

                    <div class="summary-row">
                        <span>Pemasukan</span>
                        <span>Rp {{ number_format($dataRingkasan['total_pemasukan'], 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Pengeluaran</span>
                        <span>Rp {{ number_format($dataRingkasan['total_pengeluaran'], 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row total">
                        <span>Sisa Saldo</span>
                        <span>Rp {{ number_format($dataRingkasan['sisa_saldo'], 0, ',', '.') }}</span>
                    </div>

                </div>


            </div>

            {{-- Kanan --}}
            <div class="summary-right">
                <div class="legend-card">
                    <div class="legend-item">
                        <span class="legend-dot pokok"></span>
                        <span class="percent">{{ $dataRingkasan['persentase_pokok'] }}%</span>
                        <span>Kebutuhan Pokok</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot keinginan"></span>
                        <span class="percent">{{ $dataRingkasan['persentase_keinginan'] }}%</span>
                        <span>Keinginan</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot tabungan"></span>
                        <span class="percent">{{ $dataRingkasan['persentase_tabungan'] }}%</span>
                        <span>Tabungan</span>
                    </div>
                </div>

                <button class="btn-weekly" id="weeklyDetailBtn" type="button">
                    Lihat Detail Per Minggu
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: Detail Per Minggu --}}
<div class="weekly-modal-overlay" id="weeklyModal" aria-hidden="true">
    <div class="weekly-modal" role="dialog" aria-modal="true" aria-labelledby="weeklyModalTitle">
        <div class="weekly-modal-header">
            <h2 class="weekly-modal-title" id="weeklyModalTitle">
                Bulan {{ $dataRingkasan['bulan_tahun'] }}
            </h2>

            <button type="button" class="weekly-modal-close" id="weeklyModalClose" aria-label="Tutup">
                &times;
            </button>
        </div>

        <div class="weekly-modal-grid">
            @php $bulanParam = request('bulan', now()->format('Y-m')); @endphp

            <a class="weekly-pill" href="{{ route('ringkasan.mingguan', 1) }}?bulan={{ $bulanParam }}">Minggu Ke-1</a>
            <a class="weekly-pill" href="{{ route('ringkasan.mingguan', 2) }}?bulan={{ $bulanParam }}">Minggu Ke-2</a>
            <a class="weekly-pill" href="{{ route('ringkasan.mingguan', 3) }}?bulan={{ $bulanParam }}">Minggu Ke-3</a>
            <a class="weekly-pill" href="{{ route('ringkasan.mingguan', 4) }}?bulan={{ $bulanParam }}">Minggu Ke-4</a>

        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Toggle dropdown bulan ===
    const monthToggle = document.getElementById('monthToggle');
    const monthList   = document.getElementById('monthList');

    if (monthToggle && monthList) {
        monthToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            monthList.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!monthList.contains(e.target) && !monthToggle.contains(e.target)) {
                monthList.classList.remove('show');
            }
        });
    }

    // === Modal mingguan ===
    const weeklyBtn   = document.getElementById('weeklyDetailBtn');
    const weeklyModal = document.getElementById('weeklyModal');
    const weeklyClose = document.getElementById('weeklyModalClose');

    function openWeeklyModal(){
        weeklyModal.classList.add('show');
        weeklyModal.setAttribute('aria-hidden', 'false');
    }

    function closeWeeklyModal(){
        weeklyModal.classList.remove('show');
        weeklyModal.setAttribute('aria-hidden', 'true');
    }

    if (weeklyBtn && weeklyModal && weeklyClose) {
        weeklyBtn.addEventListener('click', openWeeklyModal);
        weeklyClose.addEventListener('click', closeWeeklyModal);

        weeklyModal.addEventListener('click', (e) => {
            if (e.target === weeklyModal) closeWeeklyModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && weeklyModal.classList.contains('show')) {
                closeWeeklyModal();
            }
        });
    }

    // === Chart donut ===
    const canvas = document.getElementById('anggaranDonutChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const dataRingkasan = @json($dataRingkasan);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Kebutuhan Pokok', 'Keinginan', 'Tabungan'],
                datasets: [{
                    data: [
                        dataRingkasan.persentase_pokok,
                        dataRingkasan.persentase_keinginan,
                        dataRingkasan.persentase_tabungan
                    ],
                    backgroundColor: ['#7B7DA4', '#F8B400', '#00D394'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                rotation: -90,
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endsection
