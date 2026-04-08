<?php
include "../config/koneksi.php";

$hasil = null;

if(isset($_POST['cek'])) {
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $berat = $_POST['berat'];

    $query = mysqli_query($conn, 
        "SELECT * FROM tabel_tarif 
         WHERE kecamatan_asal='$asal' 
         AND kecamatan_tujuan='$tujuan'");

    $data = mysqli_fetch_assoc($query);

    if($data) {
        $total = $berat * $data['harga_per_kg'];
        $hasil = "Total Ongkir: Rp " . number_format($total, 0, ',', '.');
    } else {
        $hasil = "Tarif tidak ditemukan.";
    }
}
?>

<h2>Cek Ongkir Sobat Kurir</h2>

<form method="POST">
    <input type="text" name="asal" placeholder="Kecamatan Asal" required><br><br>
    <input type="text" name="tujuan" placeholder="Kecamatan Tujuan" required><br><br>
    <input type="number" name="berat" placeholder="Berat (kg)" required><br><br>
    <button type="submit" name="cek">Cek Ongkir</button>
    <a href="tracking.php">Cek Tracking</a>
</form>

<br>

<?php 
if($hasil) {
    echo "<h3>$hasil</h3>";
}
?>