@extends('layouts.nav')

@section('content')

@php
    // Cari satu insight untuk banner bawah (ambil yang pertama saja)
    $insightKategori = null;
    $insightPersen   = null;

    if (!empty($perbandingan_minggu_lalu)) {
        foreach ($perbandingan_minggu_lalu as $kat => $val) {
            if (!is_null($val)) {
                $insightKategori = $kat;
                $insightPersen   = $val;
                break;
            }
        }
    }
@endphp

<div style="padding: 32px; background-color: #F4F6FB; min-height:100vh;">

    {{-- HEADER ATAS --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div style="display:flex; align-items:center;">
            <a href="{{ route('ringkasan.index') }}"
               style="text-decoration:none; color:inherit; font-size:26px; margin-right:16px;">
                &#x2190;
            </a>
            <h2 style="font-size:32px; font-weight:600; margin:0;">
                Minggu Ke-{{ $minggu }}
            </h2>
        </div>

        {{-- tombol pilih bulan (dummy, sama seperti halaman sebelumnya) --}}
        <button style="
            display:flex;
            align-items:center;
            gap:10px;
            background:#E5E7EB;
            border-radius:999px;
            padding:10px 20px;
            border:none;
            font-weight:500;
            cursor:pointer;
        ">
            <span class="material-icons-outlined" style="font-size:18px;">calendar_today</span>
            <span>Oktober 2025</span>
            <span style="font-size:16px;">▾</span>
        </button>
    </div>

    {{-- KARTU-KARTU KATEGORI --}}
    <div style="display:flex; flex-wrap:wrap; gap:32px;">

        @foreach ($pengeluaran_mingguan as $kategori => $nominal)
            @php
                $color = '#6366f1';
                if ($kategori == 'Transport') $color = '#f59e0b';
                elseif ($kategori == 'Hiburan') $color = '#ef4444';

                $persentase = $persentase_mingguan[$kategori] ?? 0;
            @endphp

            <div style="
                width:320px;
                padding:20px 22px;
                border-radius:24px;
                background-color:#ffffff;
                box-shadow:0 12px 30px rgba(15,23,42,0.06);
            ">
                <p style="font-size:18px; font-weight:500; margin:0 0 10px 0;">
                    {{ $kategori }}
                </p>
                <p style="font-size:20px; font-weight:600; margin:0 0 14px 0;">
                    Rp {{ number_format($nominal, 2, ',', '.') }}
                </p>

                {{-- PROGRESS BAR --}}
                <div style="
                    height:10px;
                    background-color:#E5E9FF;
                    border-radius:999px;
                    overflow:hidden;
                ">
                    <div style="width: <?php echo $persentase; ?>%; height: 100%; background-color: <?php echo $color; ?>; border-radius:999px;"></div>
                </div>

                {{-- TEKS PERSEN --}}
                <span style="font-size:13px; color:#4b5563; margin-top:6px; display:block;">
                    {{ $persentase }}%
                </span>
            </div>
        @endforeach

    </div>

    {{-- BANNER INSIGHT DI BAGIAN BAWAH --}}
    @if ($insightKategori)
        <div style="margin-top:56px; display:flex; justify-content:center;">
            <div style="
                background:#6B7280;
                color:#ffffff;
                border-radius:24px;
                padding:12px 32px;
                font-size:14px;
            ">
                Pengeluaran {{ $insightKategori }} Minggu ini meningkat :
                {{ $insightPersen }}% dari minggu lalu
            </div>
        </div>
    @endif

</div>
@endsection
