<?= $this->extend('layout/template'); ?> <?= $this->section('content'); ?>

<div class="container">
    
    <div class="booking-page-container">
        <h1 class="booking-page-title">Booking / Checkout</h1>

        <div class="booking-card">
            
            <div class="booking-field-info">
                <img src="/img/fields/<?= esc($field['image'] ?? 'default.jpg'); ?>" alt="<?= esc($field['name'] ?? $field['nama']); ?>" class="booking-img">
                <div class="booking-details">
                    <h2><?= esc($field['name'] ?? $field['nama']); ?></h2>
                    <p class="field-type">Futsal / Olahraga</p>
                    
                    <p class="field-price-tag">Rp <span id="pricePerHour"><?= esc($field['price_per_hour'] ?? $field['harga']); ?></span> / jam</p>
                </div>
            </div>

            <hr class="divider">

            <form action="#" method="post" class="booking-form">
                
                <div class="form-group">
                    <label for="booking_date">Pilih Tanggal</label>
                    <input type="date" name="booking_date" id="booking_date" required class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="start_time">Jam Mulai</label>
                        <select name="start_time" id="start_time" class="form-control" onchange="calculateTotal()">
                            <option value="" disabled selected>--:--</option>
                            <?php for($i=8; $i<=22; $i++): ?>
                                <option value="<?= $i; ?>"><?= sprintf("%02d:00", $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group col-half">
                        <label for="end_time">Jam Selesai</label>
                        <select name="end_time" id="end_time" class="form-control" onchange="calculateTotal()">
                            <option value="" disabled selected>--:--</option>
                            <?php for($i=9; $i<=23; $i++): ?>
                                <option value="<?= $i; ?>"><?= sprintf("%02d:00", $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="total-price-area">
                    <span>Total Harga</span>
                    <span class="total-amount">Rp <span id="totalDisplay">0</span></span>
                </div>

                <button type="button" class="btn-booking-submit">Bayar Sekarang</button>

            </form>

        </div>
    </div>

</div>

<script>
    function calculateTotal() {
        const start = parseInt(document.getElementById('start_time').value);
        const end = parseInt(document.getElementById('end_time').value);
        
        // Mengambil teks harga, menghapus titik/koma jika ada agar jadi angka murni
        let priceText = document.getElementById('pricePerHour').innerText;
        priceText = priceText.replace(/\./g, '').replace(/,/g, ''); 
        const pricePerHour = parseInt(priceText);
        
        const totalDisplay = document.getElementById('totalDisplay');

        if (start && end && end > start) {
            const duration = end - start;
            const total = duration * pricePerHour;
            
            // Format Rupiah
            totalDisplay.innerText = total.toLocaleString('id-ID');
        } else {
            totalDisplay.innerText = "0";
        }
    }
</script>

<?= $this->endSection(); ?>