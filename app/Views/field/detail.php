<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* CSS TAMBAHAN: Untuk Slot yang sudah dibooking */
    .slot-item.disabled {
        background-color: #e0e0e0;
        color: #9e9e9e;
        cursor: not-allowed;
        border-color: #bdbdbd;
        pointer-events: none; /* Agar tidak bisa diklik */
        text-decoration: line-through;
    }
    
    /* Memastikan slot yang dipilih tetap terlihat beda */
    .slot-item.selected {
        background-color: #39E07A; /* Sesuaikan warna tema kamu */
        color: white;
        border-color: #39E07A;
    }
</style>

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
                <input type="hidden" name="venue_id" id="venueId" value="<?= $field['id'] ?>">
                <input type="hidden" name="total_harga" id="inputTotalHarga">
                <input type="hidden" name="jam_mulai" id="inputJamMulai">
                <input type="hidden" name="jam_selesai" id="inputJamSelesai">

                <div style="text-align: center; margin-bottom: 30px;">
                    <label style="font-weight: 600; display:block; margin-bottom: 10px;">Pilih Tanggal Main</label>
                    <input type="date" id="inputTanggal" name="tanggal" min="<?= date('Y-m-d') ?>" style="display: block; width: 100%; max-width: 300px; margin: 0 auto; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; text-align: center;">
                </div>

                <div class="slot-grid">
                    <?php 
                    $jam_buka = 8; 
                    $jam_tutup = 23; 
                    for ($i = $jam_buka; $i <= $jam_tutup; $i++): 
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
    const venueId = document.getElementById('venueId').value;
    
    let startHour = null;
    let endHour = null;

    // --- LOGIKA BARU: CEK KETERSEDIAAN JADWAL ---
    const inputTanggal = document.getElementById('inputTanggal');

    // Ketika tanggal berubah
    inputTanggal.addEventListener('change', function() {
        checkAvailability();
    });

    // Jalankan juga saat halaman pertama kali dimuat (jika browser menyimpan cache tanggal)
    if(inputTanggal.value) {
        checkAvailability();
    }

    function checkAvailability() {
        const date = inputTanggal.value;
        if(!date) return;

        // Reset semua pilihan saat ganti tanggal
        resetSelection();

        // Tampilkan loading (opsional, bisa dengan cursor wait)
        document.body.style.cursor = 'wait';

        // Panggil Controller
        fetch(`/booking/check-availability?venue_id=${venueId}&date=${date}`)
            .then(response => response.json())
            .then(bookedSlots => {
                // bookedSlots berisi array jam yang penuh, contoh: [12, 13, 16]
                
                // 1. Reset semua slot ke kondisi normal dulu
                document.querySelectorAll('.slot-item').forEach(el => {
                    el.classList.remove('disabled');
                    el.style.pointerEvents = 'auto'; // Aktifkan klik
                    el.title = "Tersedia";
                });

                // 2. Loop slot yang penuh dan matikan tombolnya
                bookedSlots.forEach(jam => {
                    const element = document.getElementById('slot-' + jam);
                    if(element) {
                        element.classList.add('disabled');
                        element.style.pointerEvents = 'none'; // Matikan klik via CSS
                        element.title = "Sudah dibooking";
                    }
                });

                document.body.style.cursor = 'default';
            })
            .catch(error => {
                console.error('Error:', error);
                document.body.style.cursor = 'default';
            });
    }

    function resetSelection() {
        startHour = null;
        endHour = null;
        document.querySelectorAll('.slot-item').forEach(el => {
            el.classList.remove('selected');
        });
        updateBookingInfo();
    }
    // ---------------------------------------------

    function selectSlot(element) {
        // Cek dulu apakah slot ini disabled (walaupun sudah dicegah CSS, double check di JS)
        if (element.classList.contains('disabled')) {
            return;
        }

        const jam = parseInt(element.getAttribute('data-jam'));

        if (startHour === null) {
            startHour = jam;
            highlightSlots(); 
        } 
        else if (endHour === null) {
            if (jam > startHour) {
                // Cek apakah ada slot yang dibooking DI ANTARA startHour dan jam yang baru dipilih
                // Contoh: Pilih jam 12, lalu pilih jam 15. Tapi jam 13 sudah dibooking orang.
                if (checkConflict(startHour, jam)) {
                    alert("Jadwal bentrok! Ada jam yang sudah dibooking di antara rentang waktu yang Anda pilih.");
                    return;
                }
                endHour = jam;
            } else if (jam < startHour) {
                // User klik jam mundur (misal klik 14 lalu klik 12)
                if (checkConflict(jam, startHour)) {
                    alert("Jadwal bentrok! Ada jam yang sudah dibooking.");
                    return;
                }
                endHour = startHour;
                startHour = jam;
            } else {
                // Klik slot yang sama -> Cancel selection
                startHour = null;
            }
            highlightSlots();
        } 
        else {
            // Reset dan pilih baru
            startHour = jam;
            endHour = null;
            highlightSlots();
        }

        updateBookingInfo();
    }

    // Fungsi tambahan untuk mencegah booking melewati jadwal orang lain
    function checkConflict(start, end) {
        for (let i = start; i < end; i++) {
            const el = document.getElementById('slot-' + i);
            if (el && el.classList.contains('disabled')) {
                return true; // Ada bentrok
            }
        }
        return false;
    }

    function highlightSlots() {
        // Hapus selected kecuali yang disabled
        document.querySelectorAll('.slot-item').forEach(el => {
            el.classList.remove('selected');
        });

        if (startHour !== null && endHour === null) {
            const el = document.getElementById('slot-' + startHour);
            if(el) el.classList.add('selected');
        }

        if (startHour !== null && endHour !== null) {
            for (let i = startHour; i <= endHour; i++) { // Note: logika highlight sampai endHour visualnya
                const el = document.getElementById('slot-' + i);
                // Jangan highlight kalau disabled (meski harusnya sudah dicegah checkConflict)
                if(el && !el.classList.contains('disabled')) { 
                    el.classList.add('selected');
                }
            }
        }
    }

    function updateBookingInfo() {
        let durasi = 0;
        let total = 0;

        if (startHour !== null && endHour !== null) {
            durasi = endHour - startHour; // Contoh: 13 - 12 = 1 Jam
            // Jika user memilih 12 dan 13, artinya main jam 12:00 s/d 13:00 (1 jam)
            // Note: Tergantung logikamu, kalau pilih 12 dan 13 artinya 2 jam, ubah logika di sini.
            // Tapi biasanya logika start & end selection adalah Range.
            
            // Koreksi: Jika di sistemmu klik 12 dan 12 artinya batal, 
            // tapi jika klik 12 lalu 13 artinya 1 jam (12.00-13.00), kodenya sudah benar.
            
            // Namun, jika klik 12 lalu 13 artinya main 2 jam (jam 12 dan jam 13), 
            // maka rumus durasi = (endHour - startHour) + 1.
            // Sesuai kode awalmu (durasi = endHour - startHour), saya ikuti itu.
            
            total = durasi * hargaPerJam;
            
            let startStr = startHour < 10 ? "0" + startHour + ":00:00" : startHour + ":00:00";
            let endStr = endHour < 10 ? "0" + endHour + ":00:00" : endHour + ":00:00";

            document.getElementById('inputJamMulai').value = startStr;
            document.getElementById('inputJamSelesai').value = endStr;
        } else {
            document.getElementById('inputJamMulai').value = "";
            document.getElementById('inputJamSelesai').value = "";
        }

        document.getElementById('displayDurasi').innerText = durasi;
        document.getElementById('displayTotal').innerText = total.toLocaleString('id-ID');
        document.getElementById('inputTotalHarga').value = total;
    }

    function submitForm() {
        const tgl = document.getElementById('inputTanggal').value;
        if(!tgl) {
            alert("Harap pilih tanggal main terlebih dahulu.");
            return;
        }
        if (startHour === null || endHour === null) {
            alert("Harap pilih jam mulai dan jam selesai (minimal 1 jam durasi).");
            return;
        }
        // Validasi durasi 0
        if ((endHour - startHour) <= 0) {
             alert("Durasi minimal 1 jam.");
             return;
        }

        document.getElementById('bookingForm').submit();
    }
</script>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>