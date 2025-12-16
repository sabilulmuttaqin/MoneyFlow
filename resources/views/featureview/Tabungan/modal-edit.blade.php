<div class="modal" id="modalEdit">
    <div class="modal-content edit-box">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>

        <h3 style="text-align:center; margin-bottom:10px;">Edit Tujuan Tabungan</h3>

        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="edit_id">

            <label>Nama Tujuan</label>
            <input type="text" name="nama" id="edit_nama" required>

            <label>Nominal Target (Rp)</label>
            <input type="number" name="target" id="edit_target" required>

            <label>Tabungan Sekarang (Rp)</label>
            <input type="number" name="setoran_awal" id="edit_setoran" required>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
