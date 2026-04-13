<?php
include "../config/koneksi.php";

$hasil = null;
$kecamatan_asal = "";
$kecamatan_tujuan = "";
$berat = "";

if(isset($_POST['cek'])) {
    $kecamatan_asal = $_POST['kecamatan_asal'];
    $kecamatan_tujuan = $_POST['kecamatan_tujuan'];
    $berat = $_POST['berat'];

    $query = mysqli_query($conn, "SELECT * FROM tabel_tarif 
                                  WHERE kecamatan_asal='$kecamatan_asal' 
                                  AND kecamatan_tujuan='$kecamatan_tujuan'");

    if(mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $harga_per_kg = $data['harga_per_kg'];
        $total_ongkir = $harga_per_kg * $berat;

        $hasil = [
            'kecamatan_asal' => $kecamatan_asal,
            'kecamatan_tujuan' => $kecamatan_tujuan,
            'berat' => $berat,
            'harga_per_kg' => $harga_per_kg,
            'total_ongkir' => $total_ongkir
        ];
    } else {
        $hasil = false;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Ongkir</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Cek Ongkir</h2>
    <a href="index.php">Kembali ke Beranda</a>

    <hr>

    <form method="POST">
        <label>Kecamatan Asal</label>
        <input type="text" name="kecamatan_asal" value="<?php echo $kecamatan_asal; ?>" required>

        <label>Kecamatan Tujuan</label>
        <input type="text" name="kecamatan_tujuan" value="<?php echo $kecamatan_tujuan; ?>" required>

        <label>Berat (kg)</label>
        <input type="number" name="berat" value="<?php echo $berat; ?>" step="0.01" min="1" required>

        <button type="submit" name="cek">Cek Ongkir</button>
    </form>

    <hr>

    <?php if(isset($_POST['cek'])) { ?>
        <?php if($hasil) { ?>
            <h3>Hasil Cek Ongkir</h3>
            <table>
                <tr>
                    <td><b>Kecamatan Asal</b></td>
                    <td><?php echo $hasil['kecamatan_asal']; ?></td>
                </tr>
                <tr>
                    <td><b>Kecamatan Tujuan</b></td>
                    <td><?php echo $hasil['kecamatan_tujuan']; ?></td>
                </tr>
                <tr>
                    <td><b>Berat</b></td>
                    <td><?php echo $hasil['berat']; ?> kg</td>
                </tr>
                <tr>
                    <td><b>Harga per Kg</b></td>
                    <td>Rp <?php echo number_format($hasil['harga_per_kg'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td><b>Total Ongkir</b></td>
                    <td>Rp <?php echo number_format($hasil['total_ongkir'], 0, ',', '.'); ?></td>
                </tr>
            </table>
        <?php } else { ?>
            <div class="error-box">Tarif tidak ditemukan.</div>
        <?php } ?>
    <?php } ?>

</div>
</body>
</html>