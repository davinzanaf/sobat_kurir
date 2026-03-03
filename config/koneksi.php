<?php
$conn = mysqli_connect("localhost", "root", "", "db_sobat_kurir");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>