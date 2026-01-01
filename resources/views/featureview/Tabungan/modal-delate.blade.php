<div id="modalDelete" class="delete-modal-overlay" aria-hidden="true">
    <div class="delete-modal-box" role="dialog" aria-modal="true">
        <div class="modal-header">Hapus Tabungan</div>
        <div class="modal-body" id="deleteMessage">Yakin ingin menghapus tabungan ini?</div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-footer">
                <button type="submit" class="btn-delete-confirm">Ya, Hapus</button>
                <button type="button" class="btn-cancel-delete" onclick="closeDeleteModal()">Tidak</button>
            </div>
        </form>
    </div>
</div>
