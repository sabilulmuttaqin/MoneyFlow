document.addEventListener("DOMContentLoaded", () => {
    const notif = document.getElementById("notifAlert");
    if (notif) {
        setTimeout(() => notif.remove(), 3500);
    }
});

function openEditModal(id, nama, target, setoran_awal) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_target').value = target;
    document.getElementById('edit_setoran').value = setoran_awal;

    document.getElementById('editForm').action = "/tabungan/" + id;
    document.getElementById('modalEdit').style.display = "flex";
}

function closeEditModal() {
    document.getElementById('modalEdit').style.display = 'none';
}


function openDeleteModal(id, nama) {
    document.getElementById('modalDelete').style.display = 'flex';
    document.getElementById('deleteMessage').innerHTML =
        `Apakah Anda yakin ingin menghapus tujuan <b>${nama}</b>?`;

    document.getElementById('deleteForm').action =
        `/tabungan/${id}`; // atau route('tabungan.destroy', id) jika ingin inject via blade
}

function closeDeleteModal() {
    document.getElementById('modalDelete').style.display = 'none';
}
