@extends('layouts.nav')

@section('content')
<style>
    .anggaran-page {
        font-family: "Aspekta", sans-serif;
    }
    
    .anggaran-header {
        margin-bottom: 24px;
    }
    
    .anggaran-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    
    .anggaran-card {
        background: linear-gradient(180deg, #d0e3ff 0%, #e8f1ff 50%, #f0f6ff 100%);
        border-radius: 28px;
        padding: 48px 56px;
        border: none;
        max-width: 720px;
        margin: 0 auto;
    }
    
    .anggaran-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 20px 0;
    }
    
    .anggaran-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    
    .anggaran-row:last-of-type {
        margin-bottom: 0;
    }
    
    .anggaran-label {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .anggaran-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        flex-shrink: 0;
    }
    
    .anggaran-dot.pokok { background-color: #6366f1; }
    .anggaran-dot.keinginan { background-color: #f59e0b; }
    .anggaran-dot.tabungan { background-color: #10b981; }
    
    .anggaran-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .anggaran-info span:first-child {
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
    }
    
    .anggaran-info span:last-child {
        font-size: 12px;
        color: #64748b;
    }
    
    .anggaran-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        min-width: 180px;
    }
    
    .anggaran-input-wrapper span {
        font-size: 13px;
        color: #64748b;
    }
    
    .anggaran-input-wrapper input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
    }
    
    .anggaran-input-wrapper input::placeholder {
        color: #94a3b8;
    }
    
    .btn-simpan-anggaran {
        display: block;
        margin: 32px auto 0;
        padding: 14px 48px;
        border-radius: 12px;
        border: none;
        background: #5b8def;
        color: #ffffff;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-simpan-anggaran:hover {
        background: #4c7ce5;
    }
</style>

<div class="anggaran-page">
    <div class="anggaran-header">
        <h2>Template Anggaran</h2>
    </div>

    <form action="{{ route('anggaran.storeOrUpdate') }}" method="POST">
        @csrf

        <div class="anggaran-card">
            <h3>Anggaran 50/30/20</h3>

            <!-- Kebutuhan Pokok (50%) -->
            <div class="anggaran-row">
                <div class="anggaran-label">
                    <span class="anggaran-dot pokok"></span>
                    <div class="anggaran-info">
                        <span>Kebutuhan Pokok</span>
                        <span>50%</span>
                    </div>
                </div>
                <div class="anggaran-input-wrapper">
                    <span>Rp</span>
                    <input
                        type="number"
                        name="kebutuhan_pokok"
                        value="{{ $anggaran->kebutuhan_pokok ?? 0 }}"
                        placeholder="0"
                        min="0"
                        required
                    >
                </div>
            </div>

            <!-- Keinginan (30%) -->
            <div class="anggaran-row">
                <div class="anggaran-label">
                    <span class="anggaran-dot keinginan"></span>
                    <div class="anggaran-info">
                        <span>Keinginan</span>
                        <span>30%</span>
                    </div>
                </div>
                <div class="anggaran-input-wrapper">
                    <span>Rp</span>
                    <input
                        type="number"
                        name="keinginan"
                        value="{{ $anggaran->keinginan ?? 0 }}"
                        placeholder="0"
                        min="0"
                        required
                    >
                </div>
            </div>

            <!-- Tabungan (20%) -->
            <div class="anggaran-row">
                <div class="anggaran-label">
                    <span class="anggaran-dot tabungan"></span>
                    <div class="anggaran-info">
                        <span>Tabungan</span>
                        <span>20%</span>
                    </div>
                </div>
                <div class="anggaran-input-wrapper">
                    <span>Rp</span>
                    <input
                        type="number"
                        name="tabungan"
                        value="{{ $anggaran->tabungan ?? 0 }}"
                        placeholder="0"
                        min="0"
                        required
                    >
                </div>
            </div>
        </div>

        <button type="submit" class="btn-simpan-anggaran">
            Simpan Anggaran
        </button>
    </form>
</div>
@endsection