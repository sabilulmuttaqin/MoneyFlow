@extends('layouts.nav')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/listcatatcepat.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">

        {{-- Page Header --}}
        <div class="page-header">
            <h3 class="page-title">Riwayat Transaksi</h3>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="success-alert" id="successAlert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Summary Cards - SAMA PERSIS DENGAN DASHBOARD --}}
        <div class="summary-grid">
            {{-- SALDO - STYLE DARI DASHBOARD --}}
            <div class="card card-saldo">
                <div class="card-headline">
                    <div class="card-title">Saldo <br> bulan ini</div>

                    <form method="GET" action="{{ route('catat-cepat.index') }}">
                        <select name="month" class="month-select" onchange="this.form.submit()">
                            @foreach ($availableMonths as $m)
                                <option value="{{ $m['value'] }}" {{ $m['value'] === $selectedMonth ? 'selected' : '' }}>
                                    {{ $m['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="card-main">
                    <div class="card-value-saldo">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ \Carbon\Carbon::parse($selectedMonth)->locale('id')->isoFormat('MMMM YYYY') }}
                    </div>
                </div>
            </div>

            {{-- PENGELUARAN BULANAN --}}
            <div class="card">
                <div class="card-headline">
                    <div class="card-title">Pengeluaran <br> bulanan</div>
                </div>

                <div class="card-main">
                    <div class="card-value card-value-expense">
                        Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ \Carbon\Carbon::parse($selectedMonth)->locale('id')->isoFormat('MMMM YYYY') }}
                    </div>
                </div>
            </div>

            {{-- PEMASUKAN BULANAN --}}
            <div class="card">
                <div class="card-headline">
                    <div class="card-title">Pemasukan <br> bulanan</div>
                </div>

                <div class="card-main">
                    <div class="card-value card-value-income">
                        Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                    </div>
                    <div class="card-subtitle">
                        Periode {{ \Carbon\Carbon::parse($selectedMonth)->locale('id')->isoFormat('MMMM YYYY') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Daftar Transaksi -
                    {{ \Carbon\Carbon::parse($selectedMonth)->locale('id')->isoFormat('MMMM YYYY') }}</h2>
            </div>

            @if ($transactions->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-title">Belum ada transaksi</div>
                    <div class="empty-text">Belum ada transaksi di bulan ini. Mulai catat transaksi dari dashboard.</div>
                </div>
            @else
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="activity-name">{{ $transaction->name }}</div>
                                    <div class="activity-category">{{ $transaction->category }}</div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $transaction->type }}">
                                        <span class="status-dot"></span>
                                        {{ $transaction->type === 'expense' ? 'Pengeluaran' : 'Pemasukan' }}
                                    </span>
                                </td>
                                <td class="amount-cell {{ $transaction->type }}">
                                    Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="date-cell">
                                    {{ $transaction->created_at->format('d/m/y') }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="action-btn edit"
                                            onclick="openEditModal({{ $transaction->id }}, '{{ $transaction->type }}', '{{ $transaction->category }}', '{{ $transaction->name }}', {{ $transaction->amount }})"
                                            title="Edit">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="action-btn delete"
                                            onclick="openDeleteModal({{ $transaction->id }}, '{{ $transaction->name }}')"
                                            title="Hapus">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Edit Transaksi</div>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>

            <div class="toggle-type" id="toggleType">
                <button type="button" class="toggle-btn" data-type="expense">Pengeluaran</button>
                <button type="button" class="toggle-btn" data-type="income">Pemasukan</button>
            </div>

            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" id="editType">

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" id="editCategory" class="form-input"
                        placeholder="Contoh: Makanan, Transport">
                </div>

                <div class="form-group">
                    <label class="form-label" id="editLabelName">Nama Pengeluaran</label>
                    <input type="text" name="name" id="editName" class="form-input" placeholder="Nama transaksi">
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah</label>
                    <input type="number" step="0.01" name="amount" id="editAmount" class="form-input"
                        placeholder="Jumlah">
                </div>

                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE CONFIRMATION --}}
    <div class="modal-overlay delete-modal" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Delete Transaksi</div>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="delete-modal-body">
                <div class="delete-title" id="deleteTitle">Hapus Warung Warles</div>
                <div class="delete-message">Apakah Anda yakin ingin menghapus transaksi ini?</div>
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="delete-actions">
                        <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Tidak</button>
                        <button type="submit" class="btn-delete">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // === Edit Modal ===
        let currentEditId = null;

        function openEditModal(id, type, category, name, amount) {
            currentEditId = id;
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const toggleBtns = document.querySelectorAll('#toggleType .toggle-btn');

            form.action = `/catat-cepat/${id}`;

            document.getElementById('editType').value = type;
            document.getElementById('editCategory').value = category;
            document.getElementById('editName').value = name;
            document.getElementById('editAmount').value = amount;

            toggleBtns.forEach(btn => {
                if (btn.dataset.type === type) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            document.getElementById('editLabelName').textContent =
                type === 'expense' ? 'Nama Pengeluaran' : 'Nama Pemasukan';

            modal.classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // Toggle type in edit modal
        document.querySelectorAll('#toggleType .toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#toggleType .toggle-btn').forEach(b => b.classList.remove(
                    'active'));
                btn.classList.add('active');

                const selectedType = btn.dataset.type;
                document.getElementById('editType').value = selectedType;
                document.getElementById('editLabelName').textContent =
                    selectedType === 'expense' ? 'Nama Pengeluaran' : 'Nama Pemasukan';
            });
        });

        // === Delete Modal ===
        let currentDeleteId = null;

        function openDeleteModal(id, name) {
            currentDeleteId = id;
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            form.action = `/catat-cepat/${id}`;
            document.getElementById('deleteTitle').textContent = `Hapus ${name}`;

            modal.classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        // Close modals when clicking outside
        document.getElementById('editModal').addEventListener('click', (e) => {
            if (e.target.id === 'editModal') closeEditModal();
        });

        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });

        // === Success Alert ===
        window.addEventListener('load', () => {
            const alert = document.getElementById('successAlert');
            if (!alert) return;

            requestAnimationFrame(() => {
                alert.classList.add('show');
            });

            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);

            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5500);
        });
    </script>
@endsection
