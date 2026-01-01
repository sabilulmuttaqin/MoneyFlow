@extends('layouts.nav')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">

        {{-- TOP BAR: hanya tombol Catat Cepat --}}
        <div class="top-row">
            <button class="btn-quick" id="btnOpenQuick">
                <span class="btn-quick-icon">+</span>
                <span>Catat Cepat</span>
            </button>
        </div>

        @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- ROW 1: Saldo, Pengeluaran Bulanan, Pemasukan Bulanan --}}
        <div class="summary-grid">

            {{-- Saldo --}}
            {{-- SALDO --}}
            <div class="card card-saldo">
                {{-- baris atas: judul + dropdown --}}
                <div class="card-headline">
                    <div class="card-title">Saldo <br> bulan ini</div>

                    <form method="GET" action="{{ route('dashboard') }}">
                        <input type="hidden" name="expense_month" value="{{ $expenseMonthValue }}">
                        <input type="hidden" name="income_month" value="{{ $incomeMonthValue }}">
                        <select name="saldo_month" class="month-select" onchange="this.form.submit()">
                            @foreach ($availableMonths as $m)
                                <option value="{{ $m['value'] }}"
                                    {{ $m['value'] === $saldoMonthValue ? 'selected' : '' }}>
                                    {{ $m['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- baris bawah: angka + periode --}}
                <div class="card-main">
                    <div class="card-value-saldo">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ $saldoMonthLabel }}
                    </div>
                </div>
            </div>

            {{-- PENGELUARAN BULANAN --}}
            <div class="card">
                <div class="card-headline">
                    <div class="card-title">Pengeluaran bulanan</div>

                    <form method="GET" action="{{ route('dashboard') }}">
                        <input type="hidden" name="saldo_month" value="{{ $saldoMonthValue }}">
                        <input type="hidden" name="income_month" value="{{ $incomeMonthValue }}">
                        <select name="expense_month" class="month-select" onchange="this.form.submit()">
                            @foreach ($availableMonths as $m)
                                <option value="{{ $m['value'] }}"
                                    {{ $m['value'] === $expenseMonthValue ? 'selected' : '' }}>
                                    {{ $m['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="card-main">
                    <div class="card-value card-value-expense">
                        Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ $expenseMonthLabel }}
                    </div>
                </div>
            </div>

            {{-- PEMASUKAN BULANAN --}}
            <div class="card">
                <div class="card-headline">
                    <div class="card-title">Pemasukan <br> bulanan</div>

                    <form method="GET" action="{{ route('dashboard') }}">
                        <input type="hidden" name="saldo_month" value="{{ $saldoMonthValue }}">
                        <input type="hidden" name="expense_month" value="{{ $expenseMonthValue }}">
                        <select name="income_month" class="month-select" onchange="this.form.submit()">
                            @foreach ($availableMonths as $m)
                                <option value="{{ $m['value'] }}"
                                    {{ $m['value'] === $incomeMonthValue ? 'selected' : '' }}>
                                    {{ $m['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="card-main">
                    <div class="card-value card-value-income">
                        Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ $incomeMonthLabel }}
                    </div>
                </div>
            </div>


        </div>

        {{-- ROW 2 & 3: Chart + Tabungan di kiri, Recent Activity di kanan --}}
        <div class="main-grid">
            <div class="left-column">

                {{-- Chart Batang --}}
                <div class="card card-chart">
                    <div class="card-header">
                        <div class="card-title">Ringkasan transaksi (7 hari terakhir)</div>
                    </div>

                    <div class="chart-wrapper">
                        <div class="chart-bars">
                            @foreach ($chartData as $day)
                                <div class="bar-group">
                                    <div class="bar-stack">
                                        <div class="bar-income" style="height: {{ $day['income_percent'] }}%;"></div>
                                        <div class="bar-expense" style="height: {{ $day['expense_percent'] }}%;"></div>
                                    </div>
                                    <div class="bar-label">{{ $day['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tabungan --}}
                <div class="card card-tabungan">
                    <div class="card-header">
                        <div class="card-title">Tabungan</div>
                    </div>

                    @if ($tabunganList->isEmpty())
                        <div class="tabungan-empty">
                            Belum ada data tabungan. Silakan tambahkan tabungan di menu Tabungan.
                        </div>
                    @else
                        <div class="tabungan-list">
                            @foreach ($tabunganList as $tab)
                                @php
                                    $nama   = $tab->nama;
                                    $target = $tab->target ?? 0;
                                    $saldo  = $tab->total_terkumpul ?? 0;

                                    $percent = $target > 0 ? round(($saldo / $target) * 100) : 0;
                                    $percent = min($percent, 100);
                                @endphp
                                <div class="tabungan-item">
                                    <div class="tab-title">{{ $nama }}</div>
                                    <div class="tab-target">
                                        Target: Rp {{ number_format($target, 0, ',', '.') }}<br>
                                        Sekarang: Rp {{ number_format($saldo, 0, ',', '.') }}
                                    </div>
                                    <div class="tab-progress-track">
                                        <div class="tab-progress-bar" style="width: {{ $percent }}%;"></div>
                                    </div>
                                    <div class="tab-percent">
                                        {{ $percent }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Recent Activity di kanan --}}
            <div class="right-column">
                <div class="card card-recent">
                    <div class="card-header card-header-recent">
                        <div>
                            <div class="card-title">Recent Activity</div>
                        </div>
                        <a href="{{ route('catat-cepat.index') }}" class="btn-see-all">
                            Lihat semua
                        </a>

                    </div>

                    @if ($recentActivities->isEmpty())
                        <div class="recent-empty">
                            Belum ada transaksi. Coba gunakan fitur Catat Cepat.
                        </div>
                    @else
                        <div class="recent-list">
                            @foreach ($recentActivities as $item)
                                @php
                                    $isExpense = $item->type === 'expense';
                                    $sign = $isExpense ? '-' : '+';
                                @endphp
                                <div class="recent-item">
                                    <div class="recent-left">
                                        <div class="recent-name">{{ $item->name }}</div>
                                        <div class="recent-meta">
                                            {{ $item->category->name }} · {{ $item->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <div class="recent-amount {{ $isExpense ? 'expense' : 'income' }}">
                                        {{ $sign }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CATAT CEPAT --}}
    <div class="modal-overlay" id="quickModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Catat cepat</div>
                <button class="modal-close" id="btnCloseQuick">&times;</button>
            </div>

            <div class="toggle-type" id="toggleType">
                <button type="button" class="toggle-btn active" data-type="expense">Pengeluaran</button>
                <button type="button" class="toggle-btn" data-type="income">Pemasukan</button>
            </div>

            <form method="POST" action="{{ route('catatcepat.store') }}">
                @csrf
                <input type="hidden" name="type" id="inputType" value="expense">

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-input">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" id="labelNameField">Nama Pengeluaran</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama transaksi"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah</label>
                    <input type="number" step="0.01" name="amount" class="form-input" placeholder="Jumlah"
                        value="{{ old('amount') }}">
                    @error('amount')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Simpan</button>
            </form>

            {{-- Gunakan data terakhir, dipisah per tipe, max 3 --}}
            @if (($lastExpenseTemplates ?? collect())->isNotEmpty() || ($lastIncomeTemplates ?? collect())->isNotEmpty())
                <div class="template-row">
                    <div class="template-title">Gunakan data terakhir</div>

                    {{-- Pengeluaran --}}
                    @if (($lastExpenseTemplates ?? collect())->isNotEmpty())
                        <div class="template-chips" id="templateChipsExpense">
                            @foreach ($lastExpenseTemplates as $tpl)
                                <button type="button" class="template-chip" data-type="expense"
                                    {{-- data-category="{{ $tpl->category }}" --}}data-name="{{ $tpl->name }}"
                                    data-amount="{{ $tpl->amount }}">
                                    {{-- {{ $tpl->category->name ?? '-' }} --}}{{ $tpl->name }}
                                    Rp {{ number_format($tpl->amount, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    {{-- Pemasukan --}}
                    @if (($lastIncomeTemplates ?? collect())->isNotEmpty())
                        <div class="template-chips" id="templateChipsIncome" style="display: none;">
                            @foreach ($lastIncomeTemplates as $tpl)
                                <button type="button" class="template-chip" data-type="income" {{-- data-category="{{ $tpl->category }}" --}}
                                    data-name="{{ $tpl->name }}" data-amount="{{ $tpl->amount }}">
                                    {{-- {{ $tpl->category }} · --}}{{ $tpl->name }}
                                    Rp {{ number_format($tpl->amount, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        // === Deklarasi elemen utama ===
        const btnOpenQuick = document.getElementById('btnOpenQuick');
        const btnCloseQuick = document.getElementById('btnCloseQuick');
        const modal = document.getElementById('quickModal');
        const toggleType = document.getElementById('toggleType');
        const inputType = document.getElementById('inputType');
        const labelNameField = document.getElementById('labelNameField');
        const chipsExpense = document.getElementById('templateChipsExpense');
        const chipsIncome = document.getElementById('templateChipsIncome');

        // === Modal "Catat Cepat" ===
        if (btnOpenQuick && modal && btnCloseQuick && toggleType && inputType) {
            // Buka modal
            btnOpenQuick.addEventListener('click', () => {
                modal.classList.add('show');
            });

            // Tutup modal
            btnCloseQuick.addEventListener('click', () => {
                modal.classList.remove('show');
            });

            // Tutup modal saat klik di luar area modal
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });

            // Toggle antara pengeluaran dan pemasukan
            toggleType.querySelectorAll('.toggle-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    toggleType.querySelectorAll('.toggle-btn').forEach((b) => b.classList.remove('active'));
                    btn.classList.add('active');

                    const selectedType = btn.getAttribute('data-type');
                    inputType.value = selectedType;

                    // Ubah label sesuai tipe
                    if (labelNameField) {
                        labelNameField.textContent =
                            selectedType === 'expense' ? 'Nama Pengeluaran' : 'Nama Pemasukan';
                    }

                    // Tampilkan chips sesuai tipe
                    if (chipsExpense) {
                        chipsExpense.style.display = selectedType === 'expense' ? 'flex' : 'none';
                    }
                    if (chipsIncome) {
                        chipsIncome.style.display = selectedType === 'income' ? 'flex' : 'none';
                    }
                });
            });
        }

        // === Fungsi untuk binding template chips ===
        function bindTemplateChips(container) {
            if (!container) return;

            const inputCategory = document.querySelector('input[name="category"]');
            const inputName = document.querySelector('input[name="name"]');
            const inputAmount = document.querySelector('input[name="amount"]');

            container.querySelectorAll('.template-chip').forEach((chip) => {
                chip.addEventListener('click', () => {
                    if (inputCategory) inputCategory.value = chip.getAttribute('data-category') || '';
                    if (inputName) inputName.value = chip.getAttribute('data-name') || '';
                    if (inputAmount) inputAmount.value = chip.getAttribute('data-amount') || '';
                });
            });
        }

        bindTemplateChips(chipsExpense);
        bindTemplateChips(chipsIncome);

        // === Animasi notifikasi sukses ===
        window.addEventListener('load', () => {
            const successAlert = document.querySelector('.success-alert');
            if (!successAlert) return;

            // Tampilkan (geser masuk)
            requestAnimationFrame(() => {
                successAlert.classList.add('show');
            });

            // Setelah 5 detik, geser keluar
            setTimeout(() => {
                successAlert.classList.add('hide');
            }, 5000);

            // Hapus dari DOM setelah animasi selesai
            setTimeout(() => {
                if (successAlert && successAlert.parentNode) {
                    successAlert.parentNode.removeChild(successAlert);
                }
            }, 5500);
        });
    </script>

@endsection
