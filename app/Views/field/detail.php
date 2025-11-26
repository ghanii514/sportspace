<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

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
            <p class="text-muted small">Klik jam <b>Mulai</b>, lalu klik jam <b>Selesai</b>.</p>

            <form action="/booking/summary" method="post" id="bookingForm">
                <?= csrf_field() ?>
                <input type="hidden" name="venue_id" value="<?= $field['id'] ?>">
                <input type="hidden" name="total_harga" id="inputTotalHarga">
                <input type="hidden" name="jam_mulai" id="inputJamMulai">
                <input type="hidden" name="jam_selesai" id="inputJamSelesai">

                <div style="text-align: center; margin-bottom: 30px;">
                    <label style="font-weight: 600; display:block; margin-bottom: 10px;">Pilih Tanggal Main</label>
                    <input type="date" name="tanggal" required 
                           style="display: block; width: 100%; max-width: 300px; margin: 0 auto; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; text-align: center;">
                </div>

                <div class="slot-grid">
                    <?php 
                    $jam_buka = 8; 
                    $jam_tutup = 23; 
                    for ($i = $jam_buka; $i <= $jam_tutup; $i++): // Saya ubah jadi <= agar jam tutup muncul sbg batas akhir
                        $timeLabel = sprintf("%02d.00", $i);
                    ?>
                        <div class="slot-item" id="slot-<?= $i ?>" data-jam="<?= $i ?>" onclick="selectSlot(this)">
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

                <button type="button" onclick="submitForm()" class="btn-booking-large">BOOKING SEKARANG</button>

            </form>
        </div> 
    </div> 
</div>

<script>
    const hargaPerJam = <?= $field['harga'] ?? $field['price_per_hour'] ?>;
    
    // Variabel untuk menyimpan Start dan End
    let startHour = null;
    let endHour = null;

    function selectSlot(element) {
        const jam = parseInt(element.getAttribute('data-jam'));

        // 1. Jika belum ada yang dipilih (Klik Pertama -> Set Start)
        if (startHour === null) {
            startHour = jam;
            highlightSlots(); // Update tampilan
        } 
        // 2. Jika Start sudah ada, tapi End belum (Klik Kedua -> Set End)
        else if (endHour === null) {
            if (jam > startHour) {
                // Normal: Klik jam yang lebih besar (Misal Start 9, Klik 11)
                endHour = jam;
            } else if (jam < startHour) {
                // Koreksi: Klik jam yang lebih kecil (Misal Start 11, Klik 9) -> Tukar posisi
                endHour = startHour;
                startHour = jam;
            } else {
                // Klik jam yang sama -> Batalkan pilihan
                startHour = null;
            }
            highlightSlots();
        } 
        // 3. Jika Start dan End sudah ada (Klik Ketiga -> Reset ulang)
        else {
            startHour = jam;
            endHour = null;
            highlightSlots();
        }

        updateBookingInfo();
    }

    function highlightSlots() {
        // Hapus semua seleksi dulu
        document.querySelectorAll('.slot-item').forEach(el => {
            el.classList.remove('selected');
        });

        // Jika hanya Start yang dipilih
        if (startHour !== null && endHour === null) {
            const el = document.getElementById('slot-' + startHour);
            if(el) el.classList.add('selected');
        }

        // Jika Start DAN End sudah dipilih (RANGE)
        if (startHour !== null && endHour !== null) {
            for (let i = startHour; i <= endHour; i++) {
                const el = document.getElementById('slot-' + i);
                if(el) el.classList.add('selected');
            }
        }
    }

    function updateBookingInfo() {
        let durasi = 0;
        let total = 0;

        // Hitung Durasi: End - Start
        if (startHour !== null && endHour !== null) {
            durasi = endHour - startHour;
            total = durasi * hargaPerJam;
            
            // Format MySQL Time
            let startStr = startHour < 10 ? "0" + startHour + ":00:00" : startHour + ":00:00";
            let endStr = endHour < 10 ? "0" + endHour + ":00:00" : endHour + ":00:00";

            document.getElementById('inputJamMulai').value = startStr;
            document.getElementById('inputJamSelesai').value = endStr;
        } else {
            // Reset jika belum lengkap
            document.getElementById('inputJamMulai').value = "";
            document.getElementById('inputJamSelesai').value = "";
        }

        document.getElementById('displayDurasi').innerText = durasi;
        document.getElementById('displayTotal').innerText = total.toLocaleString('id-ID');
        document.getElementById('inputTotalHarga').value = total;
    }

    function submitForm() {
        if (startHour === null || endHour === null) {
            alert("Harap pilih jam mulai dan jam selesai (minimal 1 jam durasi).");
            return;
        }
        document.getElementById('bookingForm').submit();
    }
</script>

<?= $this->renderSection('content'); ?>