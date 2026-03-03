<?php
//pengaturan database
$host       = "localhost";
$user       = "root";
$password   = ""; 
$database   = "db_sobat_kurir";

// Perintah untuk menyambungkan
$koneksi = mysqli_connect($host, $user, $password, $database);

// Mengecek apakah sambungan berhasil atau gagal
if (!$koneksi) {
    die("Aduh! Gagal tersambung ke database: " . mysqli_connect_error()); 
} else {
    echo "Mantap! Database Sobat Kurir berhasil tersambung!";
}
?>