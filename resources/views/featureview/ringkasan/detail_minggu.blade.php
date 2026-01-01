@extends('layouts.nav')

@section('content')
<style>
    .week-page{
        background: transparent;
        min-height: calc(100vh - 70px);
        padding: 0;
        font-family: "Aspekta", sans-serif;
    }

    .week-top{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
    }

    .week-left{
        display:flex;
        align-items:center;
        gap: 14px;
    }

    .back-btn{
        width: 44px;
        height: 44px;
        border-radius: 999px;
        border: none;
        background: transparent;
        cursor: pointer;
        display:grid;
        place-items:center;
        font-size: 28px;
        line-height: 1;
    }
    .back-btn:hover{ background: rgba(0,0,0,.06); }

    .week-title{
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color:#111;
        letter-spacing: -0.02em;
    }

    /* pill dropdown bulan kanan */
    .week-month{ position: relative; display:flex; justify-content: flex-end; }
    .week-month-toggle{
        display:flex;
        align-items:center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        cursor:pointer;
        font-size: 13px;
        font-weight: 500;
        color:#475569;
        min-width: auto;
        justify-content: center;
    }
    .week-month-toggle:hover{ border-color: #cbd5e1; }
    .cal-ic{ font-size: 14px; }
    .chev{ font-size: 12px; margin-left: 2px; }

    .week-month-list{
        position:absolute;
        right:0;
        top: calc(100% + 8px);
        background:#ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 8px;
        width: 180px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        display:none;
        max-height: 200px;
        overflow:auto;
        z-index: 50;
    }
    .week-month-list.show{ display:block; }
    .week-month-item{
        display:block;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration:none;
        color:#475569;
        font-weight: 500;
        font-size: 13px;
        text-align:left;
    }
    .week-month-item:hover{ background: #f1f5f9; }
    .week-month-item.active{ font-weight: 600; color: #5b8def; background: #f1f5f9; }

    /* cards */
    .week-cards{
        max-width: 100%;
        margin: 0;
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        padding-top: 0;
    }

    .week-card{
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: none;
        border: 1px solid #f1f5f9;
        min-height: auto;
        display:flex;
        flex-direction: column;
        gap: 12px;
    }

    .week-card h3{
        margin: 0;
        font-size: 14px;
        font-weight: 500;
        color:#64748b;
    }

    .week-amount{
        font-size: 18px;
        font-weight: 600;
        margin-top: 0;
        color:#0f172a;
    }

    .progress-wrap{
        margin-top: 14px;
        height: 28px;
        border-radius: 8px;
        overflow:hidden;
        display:flex;
        background: #e9ecff;
    }

    .progress-fill{
        height: 100%;
        display:flex;
        align-items:flex-end;
        padding: 4px 10px;
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
    }

    /* toast bawah */
    .week-toast{
        position: fixed;
        left: 50%;
        transform: translateX(-50%);
        bottom: 38px;
        background: rgba(90,90,90,.85);
        color: #fff;
        padding: 18px 26px;
        border-radius: 12px;
        font-size: 18px;
        max-width: 520px;
        width: calc(100% - 40px);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
        text-align: left;
    }

    @media (max-width: 900px){
        .week-title{ font-size: 34px; }
        .week-month-toggle{ min-width: 280px; font-size: 18px; }
        .week-month-list{ width: 280px; }
        .week-cards{ grid-template-columns: 1fr; max-width: 520px; }
    }
</style>

<div class="week-page">
    <div class="week-top">
      <div class="week-left">
    <button
        class="back-btn"
        type="button"
        id="backBtn"
        data-url="{{ route('ringkasan.bulanan') }}?bulan={{ $selectedMonth }}"
    >
        &#8249;
    </button>

    <h1 class="week-title">Minggu Ke-{{ $minggu }}</h1>
</div>


        <div class="week-month">
            <button type="button" class="week-month-toggle" id="weekMonthToggle">
                <span class="cal-ic">📅</span>
                <span>{{ $bulanLabel }}</span>
                <span class="chev">▼</span>
            </button>

            <div class="week-month-list" id="weekMonthList">
                @foreach ($availableMonths as $m)
                    <a class="week-month-item {{ $selectedMonth == $m['value'] ? 'active' : '' }}"
                       href="{{ route('ringkasan.mingguan', ['minggu' => $minggu]) }}?bulan={{ $m['value'] }}">
                        {{ $m['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="week-cards">
        @forelse ($cards as $c)
            <div class="week-card">
                <div>
                    <h3>{{ $c['kategori'] }}</h3>
                    <div class="week-amount">Rp. {{ number_format($c['total'], 2, ',', '.') }}</div>
                </div>

                <div class="progress-wrap">
    <div class="progress-fill"
         data-percent="{{ $c['percent'] }}"
         data-color="{{ $c['color'] }}">
        {{ $c['percent'] }}%
    </div>
</div>

            </div>
        @empty
            <div class="week-card" style="grid-column: 1 / -1; text-align:center;">
                <h3 style="font-weight:700;">Anggaran belum diatur</h3>
                <div class="week-amount" style="opacity:.7;">Silakan isi anggaran dulu</div>
            </div>
        @endforelse
    </div>
</div>

@if (!empty($toast))
    <div class="week-toast">{{ $toast }}</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function(){
    const t = document.getElementById('weekMonthToggle');
    const l = document.getElementById('weekMonthList');

    if(t && l){
        t.addEventListener('click', function(e){
            e.stopPropagation();
            l.classList.toggle('show');
        });

        document.addEventListener('click', function(e){
            if(!l.contains(e.target) && !t.contains(e.target)){
                l.classList.remove('show');
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', function(){
    const backBtn = document.getElementById('backBtn');
    if(backBtn){
        backBtn.addEventListener('click', function(){
            window.location.href = backBtn.dataset.url;
        });
    }
});



document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.progress-fill').forEach(el => {
        const pct = Number(el.dataset.percent || 0);
        const color = el.dataset.color || '#6B78FF';
        el.style.width = pct + '%';
        el.style.background = color;
    });
});







</script>
@endsection
