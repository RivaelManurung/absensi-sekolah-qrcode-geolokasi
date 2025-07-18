@extends('admin.layout.main') {{-- Guru bisa menggunakan layout admin yang sama --}}

@section('title', 'Sesi Absensi QR Code')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <span class="text-muted fw-light">Absensi /</span> Sesi QR Code
            </h5>
        </div>
        <div class="card-body text-center">
            <h4 class="card-title">Absensi untuk: {{ $schedule->subject->name }}</h4>
            <p class="card-subtitle mb-4">Kelas: <strong>{{ $schedule->class->name }}</strong></p>

            <div class="d-flex justify-content-center">
                <div id="qrcode" class="p-3 border rounded"></div>
            </div>

            <div id="countdown-wrapper" class="mt-4">
                <p class="mb-1">Sesi ini akan berakhir dalam:</p>
                <h3 class="text-danger" id="countdown"></h3>
            </div>

            <div id="expired-message" class="mt-4" style="display: none;">
                <h3 class="text-muted">SESI KEDALUWARSA</h3>
                <p>Silakan tutup halaman ini.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk membuat QR Code --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Ambil elemen-elemen yang diperlukan
    const qrCodeEl = document.getElementById('qrcode');
    const countdownEl = document.getElementById('countdown');
    const countdownWrapperEl = document.getElementById('countdown-wrapper');
    const expiredMessageEl = document.getElementById('expired-message');

    // 2. Data dari Controller
    const token = "{{ $token }}";
    const expiresAt = new Date("{{ $expires_at }}").getTime();

    // 3. Buat QR Code dari token
    new QRCode(qrCodeEl, {
        text: token,
        width: 250,
        height: 250,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    // 4. Jalankan hitung mundur
    const interval = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiresAt - now;

        if (distance < 0) {
            clearInterval(interval);
            // Jika waktu habis, sembunyikan QR & countdown, tampilkan pesan kedaluwarsa
            qrCodeEl.style.display = 'none';
            countdownWrapperEl.style.display = 'none';
            expiredMessageEl.style.display = 'block';
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Tampilkan waktu dalam format 01:30
        countdownEl.innerHTML = ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2);

    }, 1000);
});
</script>
@endpush