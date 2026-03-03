<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Tambah Tarif
if(isset($_POST['tambah'])) {
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $harga = $_POST['harga'];

    mysqli_query($conn, "INSERT INTO tabel_tarif (kecamatan_asal, kecamatan_tujuan, harga_per_kg)
                         VALUES ('$asal', '$tujuan', '$harga')");
}

// Hapus Tarif
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tabel_tarif WHERE id_tarif='$id'");
}
?>

<h2>Manajemen Tarif</h2>
<a href="dashboard.php">Kembali ke Dashboard</a>
<br><br>

<h3>Tambah Tarif</h3>
<form method="POST">
    <input type="text" name="asal" placeholder="Kecamatan Asal" required><br><br>
    <input type="text" name="tujuan" placeholder="Kecamatan Tujuan" required><br><br>
    <input type="number" name="harga" placeholder="Harga per Kg" required><br><br>
    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h3>Daftar Tarif</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Asal</th>
    <th>Tujuan</th>
    <th>Harga per Kg</th>
    <th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM tabel_tarif");

while($row = mysqli_fetch_assoc($data)) {
    echo "<tr>
            <td>{$row['kecamatan_asal']}</td>
            <td>{$row['kecamatan_tujuan']}</td>
            <td>Rp {$row['harga_per_kg']}</td>
            <td>
                <a href='?hapus={$row['id_tarif']}' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
            </td>
          </tr>";
}
?>

</table>