<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

/* TAMBAH TARIF */
if(isset($_POST['simpan'])) {
    $kecamatan_asal = $_POST['kecamatan_asal'];
    $kecamatan_tujuan = $_POST['kecamatan_tujuan'];
    $harga_per_kg = $_POST['harga_per_kg'];

    mysqli_query($conn, "INSERT INTO tabel_tarif (kecamatan_asal, kecamatan_tujuan, harga_per_kg)
                         VALUES ('$kecamatan_asal', '$kecamatan_tujuan', '$harga_per_kg')");

    header("Location: tarif.php");
    exit;
}

/* HAPUS TARIF */
if(isset($_GET['hapus'])) {
    $id_tarif = (int) $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM tabel_tarif WHERE id_tarif='$id_tarif'");

    header("Location: tarif.php");
    exit;
}

/* TAMPIL DATA TARIF */
$data_tarif = mysqli_query($conn, "SELECT * FROM tabel_tarif ORDER BY id_tarif DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tarif</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Kelola Tarif</h2>
    <p>Selamat datang, <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; ?></p>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    <a href="logout.php">Logout</a>

    <hr>

    <h3>Tambah Tarif</h3>

    <form method="POST">
        <label>Kecamatan Asal</label>
        <input type="text" name="kecamatan_asal" required>

        <label>Kecamatan Tujuan</label>
        <input type="text" name="kecamatan_tujuan" required>

        <label>Harga per Kg</label>
        <input type="number" name="harga_per_kg" required>

        <button type="submit" name="simpan">Simpan Tarif</button>
    </form>

    <hr>

    <h3>Data Tarif</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Kecamatan Asal</th>
            <th>Kecamatan Tujuan</th>
            <th>Harga per Kg</th>
            <th>Dibuat</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        if(mysqli_num_rows($data_tarif) > 0) {
            while($row = mysqli_fetch_assoc($data_tarif)) {
                echo "<tr>
                        <td>$no</td>
                        <td>{$row['kecamatan_asal']}</td>
                        <td>{$row['kecamatan_tujuan']}</td>
                        <td>Rp " . number_format($row['harga_per_kg'], 0, ',', '.') . "</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a class='btn' href='tarif.php?hapus={$row['id_tarif']}'
                               onclick=\"return confirm('Yakin ingin menghapus tarif ini?')\">Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6'>Belum ada data tarif</td></tr>";
        }
        ?>
    </table>

</div>
</body>
</html>