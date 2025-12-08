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
        display: none;           /* default: sembunyi */
        max-height: 220px;
        overflow-y: auto;
        min-width: 100%;
    }

    .month-list.show {
        display: block;
    }

    .month-item {
        display: block;
        font-size: 14px;
        padding: 3px 0;
        text-align: center;
        text-decoration: none;
        color: #111827;
    }

    .month-item:hover {
        color: #2F6BFF;
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

    .summary-row .label {
        color: #000000;
    }

    .summary-row .value {
        font-weight: 500;
        color: #000000;
    }

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

    .legend-item:last-child {
        margin-bottom: 0;
    }

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
    }

    .btn-weekly:hover {
        background: #2654d1;
        color: #ffffff;
    }

    @media (max-width: 991.98px) {
        .summary-page {
            padding: 24px 16px 32px 16px;
        }

        .summary-inner {
            max-width: 100%;
        }

        .summary-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }

        .summary-main {
            grid-template-columns: 1fr;
            row-gap: 32px;
        }

        .summary-right {
            align-items: flex-start;
            padding-top: 0;
        }

        .legend-card,
        .btn-weekly,
        .summary-box {
            width: 100%;
            max-width: 100%;
        }
    }
</style>

@php
    $tahunDropdown = date('Y'); // tahun yang mau ditampilkan
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
                    <span class="month-toggle-icon">&#9662;</span> {{-- panah ▼ --}}
                </button>

                <div class="month-list" id="monthList">
                    @foreach ($namaBulan as $m => $label)
                        {{-- Ubah href jadi route/filter punyamu sendiri kalau perlu --}}
                        <a href="#"
                           class="month-item">
                            {{ $label . ' ' . $tahunDropdown }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <div class="summary-main">
            {{-- Kiri: donut + box ringkasan --}}
            <div class="summary-left">
                <div class="donut-wrapper">
                    <canvas id="anggaranDonutChart"></canvas>
                </div>

                <div class="summary-box">
                    <div class="summary-row">
                        <span class="label">Pengeluaran</span>
                        <span class="value">Rp {{ $dataRingkasan['pengeluaran_riil'] }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Tabungan</span>
                        <span class="value">Rp {{ $dataRingkasan['tabungan_riil'] }}</span>
                    </div>
                    <hr>
                    <div class="summary-row">
                        <span class="label">Sisa Saldo</span>
                        <span class="value">Rp {{ $dataRingkasan['sisa_saldo'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Kanan: legend + tombol --}}
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

                <button class="btn-weekly">
                    Lihat Detail Per Minggu
                </button>
            </div>
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

        // === Chart donut ===
        const ctx = document.getElementById('anggaranDonutChart').getContext('2d');
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
                    backgroundColor: [
                        '#7B7DA4',
                        '#F8B400',
                        '#00D394',
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                rotation: -90,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
