<div class="modal" id="modalSetoran">
    <div class="modal-content" style="position: relative;">

        <button type="button" class="close-btn" onclick="closeSetoranModal()">×</button>

        <h3>Tambah Setoran</h3>

        <form action="{{ route('setoran.store', $tabungan->id) }}" method="POST">
            @csrf

            <label>Jumlah Setoran</label>
            <input
                type="number"
                name="jumlah"
                min="1000"
                placeholder="Minimal Rp 1.000"
                required
            >

            <button type="submit" class="btn-submit">
                Simpan
            </button>
        </form>
    </div>
</div>
