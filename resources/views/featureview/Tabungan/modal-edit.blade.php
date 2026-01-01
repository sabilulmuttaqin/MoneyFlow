<div class="modal" id="modalEdit">
    <div class="modal-content" style="position: relative;">
        <button type="button" class="close-btn" onclick="closeEditModal()">&times;</button>

        <h3>Edit Tujuan Tabungan</h3>

        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="edit_id">

            <label>Nama Tujuan</label>
            <input type="text" name="nama" id="edit_nama" required placeholder="Masukkan nama tujuan...">

            <label>Nominal Target (Rp)</label>
            <input type="number" name="target" id="edit_target" required placeholder="Masukkan nominal target">

            <label>Tabungan Sekarang (Rp)</label>
            <input type="number" name="setoran_awal" id="edit_setoran" required placeholder="Masukkan tabungan sekarang">

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
