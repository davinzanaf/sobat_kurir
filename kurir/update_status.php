<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id']) || !isset($_GET['aksi'])) {
    echo "Parameter tidak lengkap";
    exit;
}

$id_pesanan = (int) $_GET['id'];
$aksi = $_GET['aksi'];
$id_kurir = $_SESSION['id_user'];

if($aksi == 'ambil') {

    $cek = mysqli_query($conn, "SELECT * FROM tabel_pesanan 
                                WHERE id_pesanan='$id_pesanan' 
                                AND status_pesanan='MENUNGGU_KURIR'");

    if(mysqli_num_rows($cek) == 0) {
        echo "Pesanan tidak ditemukan atau sudah diambil.";
        exit;
    }

    $update = mysqli_query($conn, "UPDATE tabel_pesanan 
                                   SET id_kurir='$id_kurir', status_pesanan='DIJEMPUT'
                                   WHERE id_pesanan='$id_pesanan'");

    if(!$update) {
        die("Gagal update status: " . mysqli_error($conn));
    }

    $tracking = mysqli_query($conn, "INSERT INTO tabel_tracking (id_pesanan, keterangan)
                                     VALUES ('$id_pesanan', 'Paket sedang dijemput kurir')");

    if(!$tracking) {
        die("Gagal simpan tracking: " . mysqli_error($conn));
    }

    header("Location: dashboard.php");
    exit;

} else {
    echo "Aksi tidak dikenali";
}
?>