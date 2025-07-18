@extends('layouts.app') {{-- Gunakan layout dasar atau layout khusus siswa --}}

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="mb-0">Pindai Kode QR Absensi</h5>
                </div>
                <div class="card-body">
                    <div id="qr-reader" style="width:100%;"></div>
                    <div id="status-message" class="alert mt-3 text-center" role="alert" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk memindai QR Code --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusMessageEl = document.getElementById('status-message');
    let isRequestSent = false; // Penanda untuk mencegah pengiriman ganda

    function showMessage(message, type = 'info') {
        statusMessageEl.textContent = message;
        statusMessageEl.className = `alert alert-${type}`; // Bootstrap class: info, success, danger
        statusMessageEl.style.display = 'block';
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isRequestSent) return; // Jika request sudah dikirim, abaikan scan selanjutnya
        isRequestSent = true;
        
        // Hentikan pemindaian setelah berhasil
        html5QrcodeScanner.clear();
        showMessage('✅ QR Code terdeteksi. Mengambil lokasi GPS...', 'info');

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                showMessage('📍 Lokasi GPS didapatkan. Mengirim data ke server...', 'info');
                sendAttendance(decodedText, latitude, longitude);
            },
            (error) => {
                let errorMessage = "Gagal mendapatkan lokasi. Pastikan izin lokasi aktif.";
                if (error.code === 1) errorMessage = "Akses lokasi ditolak. Mohon izinkan akses lokasi pada browser Anda.";
                showMessage(errorMessage, 'danger');
            },
            { enableHighAccuracy: true } // Opsi untuk akurasi lebih tinggi
        );
    }

    async function sendAttendance(token, latitude, longitude) {
        try {
            const response = await fetch("{{ route('student.attendance.record') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // Diperlukan jika route ada di web.php
                },
                body: JSON.stringify({
                    token: token,
                    latitude: latitude,
                    longitude: longitude
                })
            });

            const result = await response.json();

            if (response.ok) {
                showMessage(result.message, 'success');
            } else {
                showMessage(`Gagal: ${result.message}`, 'danger');
            }
        } catch (error) {
            showMessage('Error: Tidak dapat terhubung ke server.', 'danger');
        }
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 } 
        }, 
        false
    );
    html5QrcodeScanner.render(onScanSuccess);
});
</script>
@endpush