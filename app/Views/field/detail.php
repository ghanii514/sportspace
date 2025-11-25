<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container detail-page-container">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="detail-card">
        
        <h1 class="detail-title"><?= esc($field['nama'] ?? $field['name']) ?></h1>
        <p class="detail-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <?= esc($field['alamat'] ?? $field['address']) ?>
        </p>

        <?php 
            $gambar = $field['image'] ? '/img/fields/' . $field['image'] : 'https://via.placeholder.com/800x400';
        ?>
        <img src="<?= $gambar ?>" class="detail-image" alt="<?= esc($field['nama'] ?? $field['name']) ?>">

        <div class="detail-description">
            <h4>Deskripsi Lapangan:</h4>
            <p><?= esc($field['deskripsi'] ?? 'Fasilitas lengkap, nyaman, dan strategis. Cocok untuk turnamen maupun latihan rutin.') ?></p>
        </div>

        <div class="booking-section">
            <h4>Pilih Jadwal Booking</h4>

            <form action="/booking/summary" method="post" id="bookingForm">
                <?= csrf_field() ?>
                <input type="hidden" name="venue_id" value="<?= $field['id'] ?>">
                <input type="hidden" name="total_harga" id="inputTotalHarga">
                <input type="hidden" name="jam_mulai" id="inputJamMulai">
                <input type="hidden" name="jam_selesai" id="inputJamSelesai">

                <div class="mb-4 text-center">
                    <label style="font-weight: 600; display:block; margin-bottom: 8px;">Pilih Tanggal Main</label>
                    <input type="date" name="tanggal" class="form-control" style="max-width: 300px; margin: 0 auto;" required>
                </div>

                <div class="slot-grid">
                    <?php 
                    $jam_buka = 8; 
                    $jam_tutup = 23; // Sampai jam 11 malam
                    for ($i = $jam_buka; $i < $jam_tutup; $i++): 
                        $timeLabel = sprintf("%02d.00", $i);
                    ?>
                        <div class="slot-item" data-jam="<?= $i ?>" onclick="selectSlot(this)">
                            <?= $timeLabel ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="price-summary">
                    <div class="summary-row">
                        <span>Harga Per Jam:</span>
                        <span style="font-weight: 700;">Rp <?= number_format($field['harga'] ?? $field['price_per_hour'], 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Durasi Main:</span>
                        <span><span id="displayDurasi">0</span> Jam</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Bayar:</span>
                        <span class="total-green">Rp <span id="displayTotal">0</span></span>
                    </div>
                </div>

                <button type="submit" class="btn-booking-large">BOOKING SEKARANG</button>

            </form>
        </div> </div> </div>

<script>
    // Ambil harga dari PHP
    const hargaPerJam = <?= $field['harga'] ?? $field['price_per_hour'] ?>;
    let selectedSlots = [];

    function selectSlot(element) {
        const jam = parseInt(element.getAttribute('data-jam'));

        // Cek apakah slot sudah dipilih (Toggle)
        if (selectedSlots.includes(jam)) {
            // Hapus dari array
            selectedSlots = selectedSlots.filter(j => j !== jam);
            element.classList.remove('selected');
        } else {
            // Tambah ke array
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

        // Update Input Hidden (untuk dikirim ke server)
        document.getElementById('inputTotalHarga').value = total;

        if (selectedSlots.length > 0) {
            // Urutkan jam agar rapi (misal klik 10 lalu 8 -> jadi 8, 10)
            selectedSlots.sort((a, b) => a - b);
            
            // Format untuk MySQL: Start = jam pertama, End = jam terakhir + 1
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

<?= $this->endSection('content'); ?>