<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-green: #4ade80; --dark-green: #22c55e; }
        .btn-primary-custom { background-color: var(--primary-green); border: none; color: black; font-weight: bold; }
        .btn-primary-custom:hover { background-color: var(--dark-green); color: white; }
        .slot-box { border: 1px solid #ddd; border-radius: 5px; padding: 10px; text-align: center; cursor: pointer; transition: 0.3s; }
        .slot-box.selected { background-color: var(--primary-green); color: white; border-color: var(--dark-green); }
        .slot-box.disabled { background-color: #6c757d; color: white; cursor: not-allowed; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: var(--primary-green);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">SportSpace.com</a>
        <div class="navbar-nav ms-auto">
             <a class="nav-link" href="/">Beranda</a>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12 mb-4">
            <h2 class="fw-bold"><?= esc($field['nama']) ?></h2>
            <p class="text-muted"><?= esc($field['alamat']) ?></p>
            
            <?php 
                $gambar = $field['image'] ? '/img/fields/' . $field['image'] : 'https://via.placeholder.com/800x400';
            ?>
            <img src="<?= $gambar ?>" class="img-fluid rounded w-100" style="height: 400px; object-fit: cover;" alt="<?= esc($field['nama']) ?>">
            
            <h4 class="mt-4">Deskripsi:</h4>
            <p><?= esc($field['deskripsi'] ?? 'Deskripsi belum tersedia.') ?></p>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="background-color: #e6fffa;">
        <div class="card-body">
            <h4 class="mb-3">Pilih Jadwal Booking:</h4>
            
            <form action="/booking/process" method="post" id="bookingForm">
                <?= csrf_field() ?>
                
                <input type="hidden" name="venue_id" value="<?= $field['id'] ?>">
                <input type="hidden" name="total_harga" id="inputTotalHarga">
                <input type="hidden" name="jam_mulai" id="inputJamMulai">
                <input type="hidden" name="jam_selesai" id="inputJamSelesai">

                <div class="mb-3 text-center">
                    <label class="fw-bold">Pilih Tanggal Main</label>
                    <input type="date" name="tanggal" class="form-control w-50 mx-auto" required>
                </div>

                <div class="row g-3 mb-4 text-center">
                    <?php 
                    $jam_buka = 8; 
                    $jam_tutup = 23;
                    for ($i = $jam_buka; $i < $jam_tutup; $i++): 
                        $timeLabel = sprintf("%02d.00", $i);
                    ?>
                    <div class="col-6 col-md-3">
                        <div class="slot-box" data-jam="<?= $i ?>" onclick="selectSlot(this)">
                            <?= $timeLabel ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div class="mt-4 border-top pt-3">
                    <div class="d-flex justify-content-between">
                        <span>Harga Per Jam:</span>
                        <span class="fw-bold">Rp <?= number_format($field['harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Durasi:</span>
                        <span><span id="displayDurasi">0</span> Jam</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <h4 class="fw-bold">Total Bayar:</h4>
                        <h4 class="fw-bold text-success">Rp <span id="displayTotal">0</span></h4>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">BOOKING SEKARANG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="mt-5 py-3 text-center text-muted">
    <small>&copy; 2025 SportSpace.com</small>
</footer>

<script>
    // Ambil harga dari database (PHP ke JS)
    const hargaPerJam = <?= $field['harga'] ?>;
    let selectedSlots = [];

    function selectSlot(element) {
        if (element.classList.contains('disabled')) return;
        
        const jam = parseInt(element.getAttribute('data-jam'));

        // Toggle Selection
        if (selectedSlots.includes(jam)) {
            selectedSlots = selectedSlots.filter(j => j !== jam);
            element.classList.remove('selected');
        } else {
            selectedSlots.push(jam);
            element.classList.add('selected');
        }
        updateBookingInfo();
    }

    function updateBookingInfo() {
        const durasi = selectedSlots.length;
        const total = durasi * hargaPerJam;

        // Update Text Tampilan
        document.getElementById('displayDurasi').innerText = durasi;
        document.getElementById('displayTotal').innerText = total.toLocaleString('id-ID');

        // Update Input Hidden (yang dikirim ke Controller)
        document.getElementById('inputTotalHarga').value = total;

        if (selectedSlots.length > 0) {
            // Urutkan jam agar rapi
            selectedSlots.sort((a, b) => a - b);
            
            // Format jam MySQL (HH:MM:SS)
            // Asumsi: Main 1 jam. Jika pilih jam 10, berarti 10:00 s/d 11:00
            let start = selectedSlots[0] + ":00:00"; 
            let end = (selectedSlots[selectedSlots.length - 1] + 1) + ":00:00";
            
            document.getElementById('inputJamMulai').value = start;
            document.getElementById('inputJamSelesai').value = end;
        } else {
            document.getElementById('inputJamMulai').value = "";
            document.getElementById('inputJamSelesai').value = "";
        }
    }
</script>

</body>
</html>