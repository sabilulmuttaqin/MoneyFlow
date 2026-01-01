<div id="modalTambah" class="modal">
    <div class="modal-content" style="position: relative;">
        <button type="button" class="close-btn" onclick="closeModal()">×</button>
        
        <h3>Tambah Tujuan Baru</h3>

        <form action="{{ route('tabungan.store') }}" method="POST">
            @csrf

            <label>Nama Tujuan</label>
            <input type="text" name="nama" required placeholder="Masukkan nama tujuan...">

            <label>Nominal Target (Rp)</label>
            <input type="number" name="target" required placeholder="Masukkan nominal target">

            <label>Setoran Awal (Opsional)</label>
            <input type="number" name="setoran_awal" placeholder="Masukkan setoran awal">

            <label>Tenggat (Opsional)</label>
            <input type="date" name="tenggat">

            <button type="submit" class="btn-submit">Simpan</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTambah').style.display = 'flex';
}
function closeModal() {
    document.getElementById('modalTambah').style.display = 'none';
}
</script>
