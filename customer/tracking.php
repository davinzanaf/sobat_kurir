<?php
include "../config/koneksi.php";

$data_pesanan = null;
$data_tracking = null;
$kode_resi = "";

if(isset($_POST['cek'])) {
    $kode_resi = $_POST['kode_resi'];

    $pesanan = mysqli_query($conn, "SELECT * FROM tabel_pesanan WHERE kode_resi='$kode_resi'");

    if(mysqli_num_rows($pesanan) > 0) {
        $data_pesanan = mysqli_fetch_assoc($pesanan);
        $id_pesanan = $data_pesanan['id_pesanan'];

        $data_tracking = mysqli_query($conn, "SELECT * FROM tabel_tracking 
                                              WHERE id_pesanan='$id_pesanan'
                                              ORDER BY id_tracking DESC");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Tracking</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Cek Tracking Pesanan</h2>
    <a href="index.php">Kembali ke Beranda</a>

    <hr>

    <form method="POST">
        <label>Masukkan Kode Resi</label>
        <input type="text" name="kode_resi" value="<?php echo $kode_resi; ?>" required>
        <button type="submit" name="cek">Cek Tracking</button>
    </form>

    <hr>

    <?php if(isset($_POST['cek'])) { ?>

        <?php if($data_pesanan) { ?>

            <h3>Data Pesanan</h3>
            <table>
                <tr>
                    <td><b>Kode Resi</b></td>
                    <td><?php echo $data_pesanan['kode_resi']; ?></td>
                </tr>
                <tr>
                    <td><b>Nama Pengirim</b></td>
                    <td><?php echo $data_pesanan['nama_pengirim']; ?></td>
                </tr>
                <tr>
                    <td><b>Nama Penerima</b></td>
                    <td><?php echo $data_pesanan['nama_penerima']; ?></td>
                </tr>
                <tr>
                    <td><b>Alamat Penerima</b></td>
                    <td><?php echo $data_pesanan['alamat_penerima']; ?></td>
                </tr>
                <tr>
                    <td><b>Metode Pembayaran</b></td>
                    <td><?php echo $data_pesanan['metode_pembayaran']; ?></td>
                </tr>
                <tr>
                    <td><b>Total Harga</b></td>
                    <td>Rp <?php echo number_format($data_pesanan['total_harga'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td><b>Status Pesanan</b></td>
                    <td><?php echo $data_pesanan['status_pesanan']; ?></td>
                </tr>
            </table>

            <br>

            <h3>Riwayat Tracking</h3>
            <table>
                <tr>
                    <th>No</th>
                    <th>Keterangan</th>
                    <th>Waktu Update</th>
                </tr>

                <?php
                $no = 1;
                if(mysqli_num_rows($data_tracking) > 0) {
                    while($row = mysqli_fetch_assoc($data_tracking)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>{$row['keterangan']}</td>
                                <td>{$row['waktu_update']}</td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='3'>Belum ada data tracking</td></tr>";
                }
                ?>
            </table>

        <?php } else { ?>

            <div class="error-box"><b>Kode resi tidak ditemukan.</b></div>

        <?php } ?>

    <?php } ?>

</div>
</body>
</html>