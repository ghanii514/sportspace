<?= $this->include('layout/template') ?>
    <style>
        /* Container dibuat mirip kertas folio/desain mobile */
        .container { max-width: 500px; margin: 40px auto; padding: 0 20px; }
        .page-title { font-size: 2rem; margin-bottom: 20px; font-weight: 800; }
        
        .promo-image-wrapper { text-align: center; margin-bottom: 30px; }
        .promo-image { width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        .promo-title { font-style: italic; font-size: 1.8rem; font-weight: 600; margin-bottom: 5px; color: #000; }
        .field-name { font-size: 1rem; color: #333; margin-top: 0; margin-bottom: 30px; }
        
        .terms-box { border: 2px solid #ccc; padding: 20px 30px; margin-bottom: 40px; box-shadow: 2px 2px 5px rgba(0,0,0,0.05); }
        .terms-box strong { display: block; margin-bottom: 10px; font-size: 1.1rem; }
        .terms-box ul { margin: 0; padding-left: 20px; line-height: 1.6; }
        
        .btn-action-wrapper { text-align: center; margin-bottom: 50px; }
        .btn-get-code { background-color: #39E07A; color: #000; font-weight: 800; font-size: 1.2rem; border: none; padding: 15px 60px; border-radius: 8px; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-get-code:hover { background-color: #32c96b; transform: translateY(-2px); }
        
        footer { border-top: 2px solid #39E07A; padding: 30px; font-size: 0.8rem; color: #666; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Detail Promo</h1>

        <div class="promo-image-wrapper">
            <img src="<?= base_url('img/promo/' . $promo['image']) ?>" 
                 alt="<?= esc($promo['promo']) ?>" 
                 class="promo-image"
                 onerror="this.src='https://via.placeholder.com/400x400?text=Promo+Image'">
        </div>

        <h2 class="promo-title"><?= esc($promo['promo']) ?></h2>
        
        <p class="field-name">Berlaku di Semua Cabang SportSpace</p>

        <div class="terms-box">
            <strong>Syarat & Ketentuan :</strong>
            <ul>
                <li>
                    Berlaku hingga 
                    <b>
                        <?php 
                           // Contoh logika: berlaku 30 hari sejak dibuat
                           if($promo['created_at']) {
                               echo date('d F Y', strtotime($promo['created_at'] . ' + 30 days'));
                           } else {
                               echo "Akhir Bulan Ini";
                           }
                        ?>
                    </b>.
                </li>
                
                <?php if(!empty($promo['deskripsi'])): ?>
                    <li><?= esc($promo['deskripsi']) ?></li>
                <?php endif; ?>

                <li>Satu pengguna hanya bisa pakai satu kali.</li>
                <li>Tidak dapat digabung dengan promo lain.</li>
            </ul>
        </div>

        <div class="btn-action-wrapper">
            <button class="btn-get-code" onclick="salinKode('<?= esc($promo['promo_code']) ?>')">
                Dapatkan Kode Promo
            </button>
        </div>
    </div>

    <script>
        function salinKode(kode) {
            if(!kode) {
                alert("Maaf, kode promo tidak tersedia untuk saat ini.");
                return;
            }
            // Fitur copy ke clipboard
            navigator.clipboard.writeText(kode).then(function() {
                alert("Kode Promo Tersalin: " + kode + "\nSilakan paste saat pembayaran!");
            }, function(err) {
                alert("Kode Promo Anda: " + kode);
            });
        }
    </script>
</body>
</html>
<?= $this->include('layout/footer') ?>