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

$cek = mysqli_query($conn, "SELECT * FROM tabel_pesanan WHERE id_pesanan='$id_pesanan'");
if(mysqli_num_rows($cek) == 0) {
    echo "Pesanan tidak ditemukan";
    exit;
}

$data = mysqli_fetch_assoc($cek);

if($aksi == 'ambil') {

    if($data['status_pesanan'] != 'MENUNGGU_KURIR') {
        echo "Pesanan sudah diambil atau status tidak valid";
        exit;
    }

    $update = mysqli_query($conn, "UPDATE tabel_pesanan 
                                   SET id_kurir='$id_kurir', status_pesanan='DIJEMPUT'
                                   WHERE id_pesanan='$id_pesanan'");

    if(!$update) {
        die("Gagal update status: " . mysqli_error($conn));
    }

    mysqli_query($conn, "INSERT INTO tabel_tracking (id_pesanan, keterangan)
                         VALUES ('$id_pesanan', 'Paket sedang dijemput kurir')");

    header("Location: dashboard.php");
    exit;
}

if($aksi == 'kirim') {

    if($data['id_kurir'] != $id_kurir) {
        echo "Pesanan ini bukan milik Anda";
        exit;
    }

    $update = mysqli_query($conn, "UPDATE tabel_pesanan 
                                   SET status_pesanan='DALAM_PENGIRIMAN'
                                   WHERE id_pesanan='$id_pesanan'");

    if(!$update) {
        die("Gagal update status: " . mysqli_error($conn));
    }

    mysqli_query($conn, "INSERT INTO tabel_tracking (id_pesanan, keterangan)
                         VALUES ('$id_pesanan', 'Paket sedang dalam pengiriman')");

    header("Location: detail_pesanan.php?id=$id_pesanan");
    exit;
}

if($aksi == 'selesai') {

    if($data['id_kurir'] != $id_kurir) {
        echo "Pesanan ini bukan milik Anda";
        exit;
    }

    $update = mysqli_query($conn, "UPDATE tabel_pesanan 
                                   SET status_pesanan='SELESAI'
                                   WHERE id_pesanan='$id_pesanan'");

    if(!$update) {
        die("Gagal update status: " . mysqli_error($conn));
    }

    mysqli_query($conn, "INSERT INTO tabel_tracking (id_pesanan, keterangan)
                         VALUES ('$id_pesanan', 'Pesanan telah selesai diantar')");

    header("Location: detail_pesanan.php?id=$id_pesanan");
    exit;
}

echo "Aksi tidak dikenali";
?>