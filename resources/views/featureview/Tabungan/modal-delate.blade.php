<div class="modal" id="modalDelete">
    <div class="modal-content delete-box">
        <span class="close-btn" onclick="closeDeleteModal()">&times;</span>

        <p id="deleteMessage" style="text-align:center; font-size:15px; margin-bottom:20px;">
            Apakah Anda yakin ingin menghapus?
        </p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="delete-action">
                <button type="submit" class="btn-yes">Ya</button>
                <button type="button" class="btn-no" onclick="closeDeleteModal()">Tidak</button>
            </div>
        </form>
    </div>
</div>
