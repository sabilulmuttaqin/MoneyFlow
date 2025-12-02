@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Category.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- SUCCESS TOAST --}}
<div id="successToast" class="success-alert" role="status" aria-live="polite" aria-atomic="true" style="display:none;">
    <div id="successToastMessage">Berhasil</div>
</div>

{{-- SUMMARY GRID (Background) --}}
<div class="summary-grid">
    <div class="card card-saldo">
        <div class="card-headline">
            <div class="card-title">Saldo <br>bulan ini</div>
        </div>
        <div class="card-main">
            <div class="card-value-saldo">Rp 0</div>
            <div class="card-subtitle">Periode</div>
        </div>
    </div>

    <div class="card card-expense">
        <div class="card-headline">
            <div class="card-title">Pengeluaran Bulanan</div>
        </div>
        <div class="card-main">
            <div class="card-value">Rp 0</div>
            <div class="card-subtitle">Periode</div>
        </div>
    </div>

    <div class="card card-income">
        <div class="card-headline">
            <div class="card-title">Pemasukan Bulanan</div>
        </div>
        <div class="card-main">
            <div class="card-value">Rp 0</div>
            <div class="card-subtitle">Periode</div>
        </div>
    </div>
</div>

{{-- GRID KATEGORI (Background) --}}
<div class="category-grid">
    @foreach ($categories as $cat)
        @php
            $progress = $cat->progress ?? 0;
            if($progress < 0) $progress = 0;
            if($progress > 100) $progress = 100;
        @endphp

        <div class="category-card">
            <div class="category-left">
                <div class="category-icon"><i class="bi bi-{{ $cat->icon ?? 'folder' }}"></i></div>
                <div class="category-info">
                    <div class="category-title">{{ $cat->name }}</div>
                    <div class="category-progress">
                        <div class="category-progress-fill" style="--p: {{ $progress }};"></div>
                    </div>
                    <div style="margin-top:6px; font-size:12px; color:#64748b;">
                        <strong>{{ $progress }}%</strong> tercapai
                    </div>
                </div>
            </div>
            <div class="category-right">
                <div class="category-amount">
                    Rp {{ number_format($cat->used,0,',','.') }} / Rp {{ number_format($cat->budget,0,',','.') }}
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- OVERLAY untuk modal --}}
<div id="modal-overlay" class="modal-overlay show" aria-hidden="false"></div>

{{-- EDIT FORM MODAL --}}
<div id="category-form-box" class="category-form-box" style="display: block;" aria-hidden="false" role="dialog">
    <div class="form-modal-header">
        <h3>Edit Kategori</h3>
        <a href="{{ route('kategori.index') }}" class="form-close-btn">&times;</a>
    </div>

    <form action="{{ route('kategori.update', $category) }}" method="POST" id="edit-category-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-input" 
                   placeholder="Contoh: Makanan, Transport" 
                   value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="form-group">
            <label>Jenis</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="type" value="pengeluaran" 
                           {{ old('type', $category->type) === 'pengeluaran' ? 'checked' : '' }}>
                    Pengeluaran
                </label>
                <label>
                    <input type="radio" name="type" value="pemasukan" 
                           {{ old('type', $category->type) === 'pemasukan' ? 'checked' : '' }}>
                    Pemasukan
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="budget">Jumlah Anggaran (Rp)</label>
            <input type="number" id="budget" name="budget" class="form-control" 
                   placeholder="Masukkan jumlah" 
                   value="{{ old('budget', $category->budget) }}" 
                   required min="0">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-input" required>
                <option value="">-- Pilih Status --</option>
                <option value="kebutuhan pokok" {{ old('status', $category->status) === 'kebutuhan pokok' ? 'selected' : '' }}>Kebutuhan Pokok</option>
                <option value="keinginan" {{ old('status', $category->status) === 'keinginan' ? 'selected' : '' }}>Keinginan</option>
                <option value="tabungan" {{ old('status', $category->status) === 'tabungan' ? 'selected' : '' }}>Tabungan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Icon (Bootstrap Icons)</label>
            <div class="icon-picker-wrapper">
                <input type="hidden" name="icon" id="selected-icon-input" value="{{ old('icon', $category->icon ?? 'folder') }}">
                <button type="button" class="icon-display-btn" id="icon-display-btn">
                    <i class="bi bi-{{ old('icon', $category->icon ?? 'folder') }}" id="selected-icon-preview"></i>
                    <span id="selected-icon-name">{{ old('icon', $category->icon ?? 'folder') }}</span>
                </button>
                
                <div class="icon-picker-dropdown" id="icon-picker-dropdown">
                    <input type="text" class="icon-search" id="icon-search" placeholder="Cari icon...">
                    <div class="icon-grid" id="icon-grid"></div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">Simpan Perubahan</button>
        <a href="{{ route('kategori.index') }}" class="btn-cancel">Batal</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('successToastMessage');
    const overlay = document.getElementById('modal-overlay');

    // === ICON PICKER SETUP ===
    const iconDisplayBtn = document.getElementById('icon-display-btn');
    const iconPickerDropdown = document.getElementById('icon-picker-dropdown');
    const iconSearch = document.getElementById('icon-search');
    const iconGrid = document.getElementById('icon-grid');
    const selectedIconInput = document.getElementById('selected-icon-input');
    const selectedIconPreview = document.getElementById('selected-icon-preview');
    const selectedIconName = document.getElementById('selected-icon-name');

    // Daftar icon Bootstrap yang populer
    const popularIcons = [
        'wallet2', 'cash-coin', 'currency-dollar', 'piggy-bank', 'bank', 'credit-card',
        'cart', 'bag', 'basket', 'shop', 'house', 'building', 
        'car-front', 'bus-front', 'bicycle', 'airplane', 'train-front', 'truck',
        'cup-hot', 'egg-fried', 'cake2', 'apple', 'hamburger', 'pizza',
        'heart-pulse', 'hospital', 'bandaid', 'thermometer', 'capsule', 'prescription2',
        'book', 'journal', 'pencil', 'pen', 'calculator', 'briefcase',
        'phone', 'laptop', 'tv', 'controller', 'headphones', 'camera',
        'gift', 'balloon', 'trophy', 'star', 'emoji-smile', 'balloon-heart',
        'lightning', 'cloud', 'sun', 'moon', 'flower1', 'tree',
        'hammer', 'wrench', 'gear', 'tools', 'key', 'lock',
        'folder', 'file-text', 'clipboard', 'archive', 'box', 'inbox'
    ];

    // Render icon grid
    function renderIcons(icons) {
        iconGrid.innerHTML = '';
        icons.forEach(icon => {
            const iconDiv = document.createElement('div');
            iconDiv.className = 'icon-item';
            iconDiv.innerHTML = `<i class="bi bi-${icon}"></i>`;
            iconDiv.dataset.icon = icon;
            
            if(icon === selectedIconInput.value) {
                iconDiv.classList.add('selected');
            }
            
            iconDiv.addEventListener('click', () => {
                selectedIconInput.value = icon;
                selectedIconPreview.className = `bi bi-${icon}`;
                selectedIconName.textContent = icon;
                
                // Update selected state
                document.querySelectorAll('.icon-item').forEach(item => {
                    item.classList.remove('selected');
                });
                iconDiv.classList.add('selected');
                
                // Close dropdown
                iconPickerDropdown.classList.remove('show');
            });
            
            iconGrid.appendChild(iconDiv);
        });
    }

    // Initial render
    renderIcons(popularIcons);

    // Toggle dropdown
    iconDisplayBtn.addEventListener('click', (e) => {
        e.preventDefault();
        iconPickerDropdown.classList.toggle('show');
    });

    // Search functionality
    iconSearch.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = popularIcons.filter(icon => icon.includes(searchTerm));
        renderIcons(filtered);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!iconDisplayBtn.contains(e.target) && !iconPickerDropdown.contains(e.target)) {
            iconPickerDropdown.classList.remove('show');
        }
    });

    // Close on overlay click
    if(overlay) {
        overlay.addEventListener('click', () => {
            window.location.href = "{{ route('kategori.index') }}";
        });
    }

    // Show toast if there's a success or error message
    @if(session('success'))
        toastMessage.textContent = "{{ session('success') }}";
        toast.style.display = 'flex';
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.add('hide');
            toast.classList.remove('show');
        }, 5000);
        setTimeout(() => {
            toast.style.display = 'none';
            toast.classList.remove('hide');
        }, 5500);
    @endif

    @if(session('error'))
        toastMessage.textContent = "{{ session('error') }}";
        toast.style.display = 'flex';
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.add('hide');
            toast.classList.remove('show');
        }, 5000);
        setTimeout(() => {
            toast.style.display = 'none';
            toast.classList.remove('hide');
        }, 5500);
    @endif
});
</script>
@endsection