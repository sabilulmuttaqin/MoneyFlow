<div class="modal" id="modalSetoran">
    <div class="modal-content">

        <span class="close-btn" onclick="closeSetorModal()">×</span>

        <h3>Tambah Setoran</h3>

        <form action="{{ route('setoran.store', $tabungan->id) }}" method="POST">
            @csrf

            <label>Jumlah Setoran</label>
            <input
                type="number"
                name="jumlah"
                min="1000"
                placeholder="Minimal 1.000"
                required
            >

            <button type="submit" class="btn-submit">
                Simpan
            </button>
        </form>
    </div>
</div>
