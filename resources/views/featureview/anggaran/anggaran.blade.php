{{-- resources/views/featureview/anggaran/anggaran.blade.php --}}
@extends('layouts.nav')

@section('content')
<div style="padding: 32px;">

    {{-- Judul halaman --}}
    <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 24px;">
        Template Anggaran
    </h2>

    {{-- FORM DIMULAI --}}
    <form action="{{ route('anggaran.store') }}" method="POST">
        @csrf

        {{-- Kartu besar anggaran --}}
        <div
            style="
                max-width: 720px;
                margin: 0 auto;
                padding: 32px 40px;
                border-radius: 24px;
                background: linear-gradient(180deg, #9ec5ff 0%, #e3e9f5 100%);
                box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            "
        >
            <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 24px;">
                Kebutuhan Pokok
            </h3>

            {{-- Baris 1: Kebutuhan Pokok (50%) --}}
            <div
                style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 18px;
                "
            >
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span
                        style="
                            display: inline-block;
                            width: 14px;
                            height: 14px;
                            border-radius: 999px;
                            background-color: #4f46e5;
                        "
                    ></span>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 500;">Kebutuhan Pokok</span>
                        <span style="font-size: 14px; color: #444;">50%</span>
                    </div>
                </div>

                <div
                    style="
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        background-color: rgba(255,255,255,0.8);
                        border-radius: 999px;
                        padding: 8px 16px;
                        min-width: 260px;
                    "
                >
                    <span style="font-size: 14px; color: #777;">Rp</span>
                    <input
                        type="number"
                        name="kebutuhan_pokok"
                        placeholder="0"
                        style="
                            border: none;
                            background: transparent;
                            outline: none;
                            width: 100%;
                            font-size: 14px;
                        "
                        required
                    >
                </div>
            </div>

            {{-- Baris 2: Keinginan (30%) --}}
            <div
                style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 18px;
                "
            >
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span
                        style="
                            display: inline-block;
                            width: 14px;
                            height: 14px;
                            border-radius: 999px;
                            background-color: #f59e0b;
                        "
                    ></span>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 500;">Keinginan</span>
                        <span style="font-size: 14px; color: #444;">30%</span>
                    </div>
                </div>

                <div
                    style="
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        background-color: rgba(255,255,255,0.8);
                        border-radius: 999px;
                        padding: 8px 16px;
                        min-width: 260px;
                    "
                >
                    <span style="font-size: 14px; color: #777;">Rp</span>
                    <input
                        type="number"
                        name="keinginan"
                        placeholder="0"
                        style="
                            border: none;
                            background: transparent;
                            outline: none;
                            width: 100%;
                            font-size: 14px;
                        "
                        required
                    >
                </div>
            </div>

            {{-- Baris 3: Tabungan (20%) --}}
            <div
                style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                "
            >
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span
                        style="
                            display: inline-block;
                            width: 14px;
                            height: 14px;
                            border-radius: 999px;
                            background-color: #10b981;
                        "
                    ></span>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 500;">Tabungan</span>
                        <span style="font-size: 14px; color: #444;">20%</span>
                    </div>
                </div>

                <div
                    style="
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        background-color: rgba(255,255,255,0.8);
                        border-radius: 999px;
                        padding: 8px 16px;
                        min-width: 260px;
                    "
                >
                    <span style="font-size: 14px; color: #777;">Rp</span>
                    <input
                        type="number"
                        name="tabungan"
                        placeholder="0"
                        style="
                            border: none;
                            background: transparent;
                            outline: none;
                            width: 100%;
                            font-size: 14px;
                        "
                        required
                    >
                </div>
            </div>
        </div>

        {{-- Tombol Simpan di bawah kartu --}}
        <div style="margin-top: 32px; text-align: center;">
            <button
                type="submit"
                style="
                    padding: 12px 40px;
                    border-radius: 999px;
                    border: none;
                    background-color: #2563eb;
                    color: #fff;
                    font-weight: 500;
                    font-size: 16px;
                    cursor: pointer;
                    box-shadow: 0 10px 25px rgba(37,99,235,0.35);
                "
            >
                Simpan Anggaran
            </button>
        </div>

    </form>
    {{-- FORM DI TUTUP --}}

</div>
@endsection
