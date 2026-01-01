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
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", sans-serif;
        background: var(--mf-soft-bg);
        min-height: calc(100vh - 70px);
        padding: 32px 56px 40px 56px;
        display: flex;
        justify-content: center;
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
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--mf-text-main);
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
        border-radius: 999px;
        border: none;
        background: #E5E6EB;
        padding: 11px 26px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        font-size: 15px;
        color: #000000;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.25);
        cursor: pointer;
    }

    .month-toggle-icon {
        font-size: 13px;
        margin-left: 4px;
    }

    .month-list {
        position: absolute;
        right: 0;
        margin-top: 8px;
        padding: 12px 22px;
        background: #E5E6EB;
        border-radius: 28px;
        box-shadow: 0 26px 55px rgba(15, 23, 42, 0.35);
        display: none;
        max-height: 220px;
        overflow-y: auto;
        min-width: 100%;
        z-index: 50;
    }

    .month-list.show {
        display: block;
    }

    .month-item {
        display: block;
        font-size: 14px;
        padding: 6px 0;
        text-align: center;
        text-decoration: none;
        color: #111827;
        border-radius: 10px;
    }

    .month-item:hover {
        color: #2F6BFF;
        text-decoration: underline;
    }

    .month-item.active {
        color: #2F6BFF;
        font-weight: 700;
        text-decoration: underline;
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
        background: linear-gradient(145deg, #C3D5FF 0%, #E6EBFF 40%, #F4F4F7 100%);
        border-radius: 38px;
        padding: 22px 30px;
        width: 340px;
        max-width: 100%;
        box-shadow: 0 22px 45px rgba(148, 163, 184, 0.6);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .summary-row .label { color: #000000; }
    .summary-row .value { font-weight: 500; color: #000000; }

    .summary-box hr {
        margin: 10px 0 8px;
        border-top: 1px solid rgba(148, 163, 184, 0.45);
    }

    .legend-card {
        background: linear-gradient(135deg, #E0EDFF 0%, #F4F7FF 100%);
        border-radius: 999px;
        padding: 18px 34px;
        box-shadow: 0 25px 42px rgba(148, 163, 184, 0.6);
        min-width: 360px;
        max-width: 360px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 18px;
        font-weight: 600;
        color: #000000;
        margin-bottom: 6px;
    }

    .legend-item:last-child { margin-bottom: 0; }

    .legend-dot {
        width: 16px;
        height: 16px;
        border-radius: 999px;
        display: inline-block;
    }

    .legend-dot.pokok     { background: var(--mf-pokok); }
    .legend-dot.keinginan { background: var(--mf-keinginan); }
    .legend-dot.tabungan  { background: var(--mf-tabungan); }

    .legend-item .percent {
        min-width: 52px;
        font-weight: 700;
    }

    .btn-weekly {
        border-radius: 999px;
        background: var(--mf-primary-blue);
        border: none;
        padding: 16px 32px;
        font-size: 17px;
        font-weight: 600;
        color: #ffffff;
        width: 360px;
        max-width: 360px;
        box-shadow: 0 22px 40px rgba(47, 107, 255, 0.7);
        text-align: center;
        cursor: pointer;
    }

    .btn-weekly:hover {
        background: #2654d1;
        color: #ffffff;
    }

    /* ===== MODAL DETAIL MINGGUAN (JANGAN DI DALAM @media) ===== */
    .weekly-modal-overlay{
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.35);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 18px;
    }

    .weekly-modal-overlay.show{ display: flex; }

    .weekly-modal{
        width: 720px;
        max-width: 100%;
        border-radius: 34px;
        background: radial-gradient(circle at 30% 20%, #F6FAFF 0%, #EAF2FF 40%, #DCE9FF 100%);
        box-shadow: 0 28px 70px rgba(15,23,42,.35);
        padding: 28px 30px 30px;
        position: relative;
    }

    .weekly-modal-header{
        display:flex;
        align-items:center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .weekly-modal-title{
        font-size: 26px;
        font-weight: 500;
        color: #0f172a;
        margin: 0;
    }

    .weekly-modal-close{
        width: 44px;
        height: 44px;
        border: none;
        background: transparent;
        font-size: 32px;
        line-height: 1;
        cursor: pointer;
        color: #0f172a;
        display: grid;
        place-items: center;
        border-radius: 999px;
    }
    .weekly-modal-close:hover{ background: rgba(15,23,42,.06); }

    .weekly-modal-grid{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px 28px;
    }

    .weekly-pill{
        display:flex;
        align-items:center;
        justify-content:center;
        height: 64px;
        border-radius: 999px;
        background: #4B86FF;
        color: #ffffff;
        font-size: 20px;
        font-weight: 500;
        text-decoration: none;
        box-shadow: 0 18px 40px rgba(75,134,255,.35);
        border: none;
        cursor: pointer;
    }
    .weekly-pill:hover{ filter: brightness(.95); }

    @media (max-width: 560px){
        .weekly-modal-title{ font-size: 20px; }
        .weekly-modal-grid{ grid-template-columns: 1fr; }
        .weekly-pill{ height: 58px; font-size: 18px; }
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
