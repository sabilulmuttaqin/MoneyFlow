@if(session('success_setoran'))
<div class="modal show" id="successModal">
    <div class="success-content">

        <div class="icon">
            💰
        </div>

        <h3>Setoran</h3>
        <h2>Rp {{ number_format(session('success_setoran.jumlah'), 0, ',', '.') }}</h2>
        <p><b>Berhasil!</b></p>

        <small>
            Kamu sudah mencapai
            <b>{{ session('success_setoran.progress') }}%</b>
            dari target
        </small>

        <button onclick="closeSuccessModal()" class="btn-primary">
            Tutup
        </button>

    </div>
</div>
@endif
