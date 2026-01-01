@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Category.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- SUCCESS TOAST --}}
<div id="successToast" class="success-alert" role="status" aria-live="polite" aria-atomic="true" style="display:none;">
    <div id="successToastMessage">Berhasil</div>
</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="delete-modal-overlay" aria-hidden="true">
    <div class="delete-modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="modal-header" id="deleteModalTitle">Hapus Kategori</div>
        <div class="modal-body" id="deleteMessage">Yakin ingin menghapus kategori ini?</div>
        <div class="modal-footer">
            <button id="confirmDeleteBtn" class="btn-delete-confirm">Ya, Hapus</button>
            <button id="cancelDeleteBtn" class="btn-cancel-delete">Tidak</button>
        </div>
    </div>
</div>

{{-- OVERLAY untuk modal --}}
<div id="modal-overlay" class="modal-overlay" aria-hidden="true"></div>

{{-- CREATE FORM MODAL --}}
<div id="category-form-box" class="category-form-box" aria-hidden="true" role="dialog">
    <div class="form-modal-header">
        <h3>Buat Kategori</h3>
        <button class="form-close-btn" id="formCloseBtn">&times;</button>
    </div>

    <form id="create-category-form" method="POST" action="{{ route('kategori.store') }}">
        @csrf

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-input" placeholder="Contoh: Makanan, Transport" required>
        </div>

        <div class="form-group">
            <label>Jenis</label>
            <div class="radio-group">
                <label><input type="radio" name="type" value="pengeluaran" checked> Pengeluaran</label>
                <label><input type="radio" name="type" value="pemasukan"> Pemasukan</label>
            </div>
        </div>

        <div class="form-group">
            <label for="budget">Jumlah / Budget</label>
            <input type="number" name="budget" id="budget" class="form-control" placeholder="Masukkan jumlah" required min="0">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-input" required>
                <option value="">-- Pilih Status --</option>
                <option value="kebutuhan pokok">Kebutuhan Pokok</option>
                <option value="keinginan">Keinginan</option>
                <option value="tabungan">Tabungan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Icon (Bootstrap Icons)</label>
            <div class="icon-picker-wrapper">
                <input type="hidden" name="icon" id="selected-icon-input" value="folder">
                <button type="button" class="icon-display-btn" id="icon-display-btn">
                    <i class="bi bi-folder" id="selected-icon-preview"></i>
                    <span id="selected-icon-name">folder</span>
                </button>
                <div class="icon-picker-dropdown" id="icon-picker-dropdown">
                    <input type="text" class="icon-search" id="icon-search" placeholder="Cari icon...">
                    <div class="icon-grid" id="icon-grid"></div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">Simpan</button>
    </form>
</div>

{{-- SUMMARY GRID --}}
<div class="summary-grid">
    {{-- Saldo --}}
    <div class="card card-saldo">
        <div class="card-headline">
            <div class="card-title">Saldo <br>bulan ini</div>
            <form method="GET" action="{{ route('kategori.index') }}">
                <input type="hidden" name="expense_month" value="{{ $expenseMonthValue }}">
                <input type="hidden" name="income_month" value="{{ $incomeMonthValue }}">
                <select name="saldo_month" class="month-select" onchange="this.form.submit()">
                    @foreach ($availableMonths as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] === $saldoMonthValue ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-main">
            <div class="card-value-saldo">Rp {{ number_format($balance,0,',','.') }}</div>
            <div class="card-subtitle">Periode {{ $saldoMonth->translatedFormat('F Y') }}</div>
        </div>
    </div>

    {{-- Pengeluaran --}}
    <div class="card card-expense">
        <div class="card-headline">
            <div class="card-title">Pengeluaran Bulanan</div>
            <form method="GET" action="{{ route('kategori.index') }}">
                <input type="hidden" name="saldo_month" value="{{ $saldoMonthValue }}">
                <input type="hidden" name="income_month" value="{{ $incomeMonthValue }}">
                <select name="expense_month" class="month-select" onchange="this.form.submit()">
                    @foreach ($availableMonths as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] === $expenseMonthValue ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-main">
            <div class="card-value">Rp {{ number_format($monthlyExpense,0,',','.') }}</div>
            <div class="card-subtitle">Periode {{ $expenseMonth->translatedFormat('F Y') }}</div>
        </div>
    </div>

    {{-- Pemasukan --}}
    <div class="card card-income">
        <div class="card-headline">
            <div class="card-title">Pemasukan <br> Bulanan</div>
            <form method="GET" action="{{ route('kategori.index') }}">
                <input type="hidden" name="saldo_month" value="{{ $saldoMonthValue }}">
                <input type="hidden" name="expense_month" value="{{ $expenseMonthValue }}">
                <select name="income_month" class="month-select" onchange="this.form.submit()">
                    @foreach ($availableMonths as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] === $incomeMonthValue ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-main">
            <div class="card-value">Rp {{ number_format($monthlyIncome,0,',','.') }}</div>
            <div class="card-subtitle">Periode {{ $incomeMonth->translatedFormat('F Y') }}</div>
        </div>
    </div>
</div>

{{-- HEADER --}}
<div class="page-header mb-4">
    <h2>Kategori</h2>
    <button id="btn-add-category" class="btn-add-category"><i class="bi bi-plus-circle"></i> Tambah Kategori</button>
</div>

{{-- GRID KATEGORI --}}
<div id="category-grid" class="category-grid" aria-live="polite">
    @foreach ($categories as $category)
        @php
            $progress = max(0, min(100, $category->progress));
        @endphp
        <div class="category-card" data-id="{{ $category->id }}">
            <div class="category-left">
                <div class="category-icon"><i class="bi bi-{{ $category->icon ?? 'folder' }}"></i></div>
                <div class="category-info">
                    <div class="category-title">{{ $category->name }}</div>
                    <div class="category-progress"><div class="category-progress-fill" style="--p: {{ $progress }};"></div></div>
                    <div style="margin-top:6px; font-size:12px; color:#64748b;"><strong>{{ $progress }}%</strong> tercapai</div>
                </div>
            </div>
            <div class="category-right">
                <div class="category-amount">Rp {{ number_format($category->used,0,',','.') }} / Rp {{ number_format($category->budget,0,',','.') }}</div>
                <div style="display:flex; gap:8px; margin-top:6px;">
                    <a href="{{ route('kategori.edit', $category->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                    <button class="delete-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ELEMENTS
    const overlay = document.getElementById('modal-overlay');
    const formBox = document.getElementById('category-form-box');
    const btnAdd = document.getElementById('btn-add-category');
    const formClose = document.getElementById('formCloseBtn');
    const createForm = document.getElementById('create-category-form');
    const categoryGrid = document.getElementById('category-grid');
    const deleteModal = document.getElementById('deleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('successToastMessage');

    let deleteTargetId = null;

    // ICON PICKER
    const iconDisplayBtn = document.getElementById('icon-display-btn');
    const iconPickerDropdown = document.getElementById('icon-picker-dropdown');
    const iconSearch = document.getElementById('icon-search');
    const iconGrid = document.getElementById('icon-grid');
    const selectedIconInput = document.getElementById('selected-icon-input');
    const selectedIconPreview = document.getElementById('selected-icon-preview');
    const selectedIconName = document.getElementById('selected-icon-name');

    const popularIcons = [
        'wallet2','cash-coin','currency-dollar','piggy-bank','bank','credit-card',
        'cart','bag','basket','shop','house','building',
        'car-front','bus-front','bicycle','airplane','train-front','truck',
        'cup-hot','egg-fried','cake2','apple','hamburger','pizza',
        'heart-pulse','hospital','bandaid','thermometer','capsule','prescription2',
        'book','journal','pencil','pen','calculator','briefcase',
        'phone','laptop','tv','controller','headphones','camera',
        'gift','balloon','trophy','star','emoji-smile','balloon-heart',
        'lightning','cloud','sun','moon','flower1','tree',
        'hammer','wrench','gear','tools','key','lock',
        'folder','file-text','clipboard','archive','box','inbox'
    ];

    function renderIcons(icons){
        iconGrid.innerHTML='';
        icons.forEach(icon=>{
            const div=document.createElement('div');
            div.className='icon-item';
            div.innerHTML=`<i class="bi bi-${icon}"></i>`;
            div.dataset.icon=icon;
            if(icon===selectedIconInput.value) div.classList.add('selected');
            div.addEventListener('click',()=>{
                selectedIconInput.value=icon;
                selectedIconPreview.className=`bi bi-${icon}`;
                selectedIconName.textContent=icon;
                document.querySelectorAll('.icon-item').forEach(i=>i.classList.remove('selected'));
                div.classList.add('selected');
                iconPickerDropdown.classList.remove('show');
            });
            iconGrid.appendChild(div);
        });
    }

    // Initial render
    renderIcons(popularIcons);

    iconDisplayBtn.addEventListener('click', e=>{
        e.preventDefault();
        iconPickerDropdown.classList.toggle('show');
        if(iconSearch.value.trim()==='') renderIcons(popularIcons);
    });

    iconSearch.addEventListener('input', e=>{
        const searchTerm=e.target.value.toLowerCase();
        renderIcons(popularIcons.filter(i=>i.includes(searchTerm)));
    });

    document.addEventListener('click', e=>{
        if(!iconDisplayBtn.contains(e.target) && !iconPickerDropdown.contains(e.target)){
            iconPickerDropdown.classList.remove('show');
        }
    });

    // OVERLAY
    function showOverlay(){ overlay.classList.add('show'); overlay.setAttribute('aria-hidden','false'); }
    function hideOverlay(){ overlay.classList.remove('show'); overlay.setAttribute('aria-hidden','true'); }

    // FORM
    function openForm(){ formBox.style.display='block'; formBox.setAttribute('aria-hidden','false'); showOverlay(); }
    function closeForm(){ formBox.style.display='none'; formBox.setAttribute('aria-hidden','true'); hideOverlay(); }
    btnAdd?.addEventListener('click', openForm);
    formClose?.addEventListener('click', closeForm);
    overlay?.addEventListener('click', e => { if(e.target===overlay) closeForm(); });

    // TOAST
    function showToast(message){
        toastMessage.textContent=message;
        toast.style.display='flex';
        void toast.offsetWidth;
        toast.classList.add('show');
        setTimeout(()=>{ toast.classList.add('hide'); toast.classList.remove('show'); }, 5000);
        setTimeout(()=>{ toast.style.display='none'; toast.classList.remove('hide'); },5500);
    }
    @if(session('success')) showToast("{{ session('success') }}"); @endif
    @if(session('error')) showToast("{{ session('error') }}"); @endif

    // CREATE AJAX
    if(createForm){
        createForm.addEventListener('submit', async e=>{
            e.preventDefault();
            const fd=new FormData(createForm);
            try{
                const res=await fetch(createForm.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:fd});
                const data=await res.json();
                if(data.success){
                    const c=data.category;
                    const percent=0;
                    const cardHtml=`
                    <div class="category-card" data-id="${c.id}">
                        <div class="category-left">
                            <div class="category-icon"><i class="bi bi-${c.icon || 'folder'}"></i></div>
                            <div class="category-info">
                                <div class="category-title">${escapeHtml(c.name)}</div>
                                <div class="category-progress"><div class="category-progress-fill" style="--p: ${percent};"></div></div>
                                <div style="margin-top:6px;font-size:12px;color:#64748b;"><strong>${percent}%</strong> tercapai</div>
                            </div>
                        </div>
                        <div class="category-right">
                            <div class="category-amount">Rp 0 / Rp ${Number(c.budget).toLocaleString('id-ID')}</div>
                            <div style="display:flex;gap:8px;margin-top:6px;">
                                <a href="/kategori/${c.id}/edit" class="btn btn-sm btn-primary" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <button class="delete-btn" data-id="${c.id}" data-name="${escapeHtml(c.name)}" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </div>
                        </div>
                    </div>`;
                    categoryGrid.insertAdjacentHTML('beforeend', cardHtml);
                    setupDeleteButton(categoryGrid.querySelector(`.delete-btn[data-id="${c.id}"]`));
                    showToast(data.message || 'Kategori berhasil ditambahkan!');
                    createForm.reset();
                    selectedIconInput.value='folder';
                    selectedIconPreview.className='bi bi-folder';
                    selectedIconName.textContent='folder';
                    closeForm();
                } else showToast(data.message || 'Gagal membuat kategori');
            }catch(err){ console.error(err); showToast('Terjadi kesalahan'); }
        });
    }

    // DELETE
    function setupDeleteButton(btn){
        btn.addEventListener('click',()=>{ deleteTargetId=btn.dataset.id; deleteMessage.textContent=`Yakin ingin menghapus kategori "${btn.dataset.name}"?`; deleteModal.style.display='flex'; deleteModal.classList.add('show'); });
    }
    document.querySelectorAll('.delete-btn').forEach(setupDeleteButton);
    cancelDeleteBtn?.addEventListener('click',()=>{ deleteModal.style.display='none'; deleteModal.classList.remove('show'); deleteTargetId=null; });
    confirmDeleteBtn?.addEventListener('click',async ()=>{
        if(!deleteTargetId) return;
        try{
            const res=await fetch("{{ route('kategori.destroy', ':id') }}".replace(':id', deleteTargetId),{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}});
            const data=await res.json();
            if(data.success){
                document.querySelector(`.category-card[data-id="${data.id}"]`)?.remove();
                showToast(data.message || 'Kategori berhasil dihapus!');
            } else showToast(data.message || 'Gagal menghapus kategori');
        }catch(err){ console.error(err); showToast('Terjadi kesalahan'); }
        finally{ deleteModal.style.display='none'; deleteModal.classList.remove('show'); deleteTargetId=null; }
    });

    // ESCAPE HTML
    function escapeHtml(unsafe){ return (''+unsafe).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;"); }
});
</script>
@endsection
