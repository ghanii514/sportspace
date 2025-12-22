<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Global Styles */
    :root {
        --primary-color: #10b981;
        --primary-dark: #059669;
        --bg-color: #f3f4f6;
        --text-dark: #1f2937;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-dark);
    }

    /* --- BAGIAN INI YANG MENGATUR POSISI TENGAH (FLEXBOX) --- */
    .main-wrapper {
        display: flex;             /* Mengaktifkan Flexbox */
        justify-content: center;   /* Membuat anak elemen berada di tengah secara horizontal */
        width: 100%;               /* Lebar penuh */
    }

    .edit-container {
        max-width: 600px;
        width: 100%;               /* Agar responsif */
        
        /* Jarak Atas & Bawah 100px, Kiri & Kanan 0 (Karena sudah ditangani Flexbox) */
        margin-top: 100px;
        margin-bottom: 100px;
        
        padding: 0 20px;
    }
    /* --------------------------------------------------------- */

    .card {
        background: var(--white);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    h2 {
        margin-top: 0;
        color: var(--text-dark);
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-group { margin-bottom: 20px; }

    label {
        display: block; margin-bottom: 8px; font-weight: 500; color: #4b5563; font-size: 0.95em;
    }

    input[type="text"], input[type="email"], input[type="file"] {
        width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #d1d5db;
        font-family: 'Poppins', sans-serif; font-size: 0.95em; outline: none; transition: 0.3s;
        box-sizing: border-box;
    }

    input[type="text"]:focus, input[type="email"]:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    input[readonly] { background-color: #f9fafb; color: #9ca3af; cursor: not-allowed; border-color: #e5e7eb; }

    .img-preview-container { text-align: center; margin-bottom: 20px; }
    .img-preview {
        width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
        border: 3px solid var(--primary-color); padding: 3px;
    }

    .btn-group { display: flex; gap: 15px; margin-top: 30px; }
    
    .btn {
        flex: 1; padding: 12px; border-radius: 10px; border: none; font-weight: 600;
        cursor: pointer; text-align: center; text-decoration: none; font-size: 1rem;
        transition: 0.2s; display: inline-block;
    }

    .btn-save { background-color: var(--primary-color); color: white; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3); }
    .btn-save:hover { background-color: var(--primary-dark); transform: translateY(-2px); }
    .btn-cancel { background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }
    .btn-cancel:hover { background-color: #e5e7eb; color: #1f2937; }

    .alert-error {
        background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9em;
    }
    .alert-error ul { margin: 0; padding-left: 20px; }
</style>

<div class="main-wrapper">
    
    <div class="edit-container">
        <div class="card">
            <h2>Edit Profil</h2>

            <?php if (session()->has('errors')) : ?>
                <div class="alert-error">
                    <ul>
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="/profile/update" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="img-preview-container">
                    <img src="/img/users/<?= user()->profile_picture ?? 'default.png' ?>" class="img-preview" id="imgPreview">
                </div>
                
                <div class="form-group">
                    <label for="foto" style="text-align:center; font-size:0.8em; color:#10b981; cursor:pointer;">[ Ubah Foto ]</label>
                    <input type="file" id="foto" name="foto" onchange="previewImage()" style="display:none;">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= old('username', user()->username) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email <span style="font-weight:400; font-size:0.8em; color:#ef4444;">(Tidak dapat diubah)</span></label>
                    <input type="email" id="email" value="<?= user()->email ?>" readonly>
                </div>

                <div class="btn-group">
                    <a href="/profile" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div> <script>
    // Trigger input file saat teks [Ubah Foto] diklik (Agar lebih rapi)
    document.querySelector('label[for="foto"]').addEventListener('click', function() {
        document.getElementById('foto').click();
    });

    function previewImage() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('#imgPreview');

        if(foto.files && foto.files[0]){
            const fileFoto = new FileReader();
            fileFoto.readAsDataURL(foto.files[0]);

            fileFoto.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
    }
</script>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>