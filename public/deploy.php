<?php
// Secret Token untuk keamanan agar tidak sembarang orang bisa menjalankan script ini
$secret = 'eraporsd_rahasia';

// Cek apakah token yang dikirim benar
if (!isset($_GET['token']) || $_GET['token'] !== $secret) {
    header('HTTP/1.0 403 Forbidden');
    die('Akses Ditolak!');
}

// Pindah ke folder utama project (satu tingkat di atas folder public)
chdir(__DIR__ . '/../');

// Jalankan perintah git pull
$output = shell_exec('git pull origin main 2>&1');

// Tampilkan hasil
echo "Sukses!\n";
echo "<pre>$output</pre>";
?>
