@extends('layouts.nav')

@section('content')
<style>
    .week-page{
        background:#f2f2f2;
        min-height: calc(100vh - 70px);
        padding: 28px 36px 40px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", sans-serif;
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
        font-size: 44px;
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
        gap: 14px;
        padding: 16px 26px;
        border-radius: 999px;
        border: none;
        background: #d9d9d9;
        cursor:pointer;
        font-size: 22px;
        font-weight: 600;
        color:#111;
        min-width: 340px;
        justify-content: center;
    }
    .week-month-toggle:hover{ filter: brightness(.98); }
    .cal-ic{ font-size: 22px; }
    .chev{ font-size: 18px; margin-left: 4px; }

    .week-month-list{
        position:absolute;
        right:0;
        top: calc(100% + 10px);
        background:#e5e5e5;
        border-radius: 20px;
        padding: 10px 14px;
        width: 340px;
        box-shadow: 0 18px 40px rgba(0,0,0,.18);
        display:none;
        max-height: 240px;
        overflow:auto;
        z-index: 50;
    }
    .week-month-list.show{ display:block; }
    .week-month-item{
        display:block;
        padding: 10px 8px;
        border-radius: 14px;
        text-decoration:none;
        color:#111;
        font-weight: 500;
        text-align:center;
    }
    .week-month-item:hover{ background: rgba(0,0,0,.06); }
    .week-month-item.active{ font-weight: 800; text-decoration: underline; }

    /* cards */
    .week-cards{
        max-width: 880px;
        margin: 0 auto;
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 34px 58px;
        padding-top: 10px;
    }

    .week-card{
        background: #ffffff;
        border-radius: 10px;
        padding: 18px 22px 16px;
        box-shadow: 0 10px 24px rgba(0,0,0,.06);
        min-height: 120px;
        display:flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .week-card h3{
        margin: 0;
        font-size: 26px;
        font-weight: 400;
        color:#111;
    }

    .week-amount{
        font-size: 22px;
        font-weight: 500;
        margin-top: 12px;
        color:#111;
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
        box-shadow: 0 18px 40px rgba(0,0,0,.2);
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
