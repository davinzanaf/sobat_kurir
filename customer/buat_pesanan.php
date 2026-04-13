<?php
include "../config/koneksi.php";

$pesan = "";

$asal_terpilih = "";
$tujuan_terpilih = "";
$berat_terisi = "";
$metode_terpilih = "COD";

$asal_list = mysqli_query($conn, "SELECT DISTINCT kecamatan_asal FROM tabel_tarif ORDER BY kecamatan_asal ASC");
$tujuan_list = mysqli_query($conn, "SELECT DISTINCT kecamatan_tujuan FROM tabel_tarif ORDER BY kecamatan_tujuan ASC");

if(isset($_POST['buat'])) {

    $nama_pengirim = $_POST['nama_pengirim'];
    $hp_pengirim = $_POST['hp_pengirim'];
    $alamat_pengirim = $_POST['alamat_pengirim'];

    $nama_penerima = $_POST['nama_penerima'];
    $hp_penerima = $_POST['hp_penerima'];
    $alamat_penerima = $_POST['alamat_penerima'];

    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $berat = (float) $_POST['berat'];
    $metode = $_POST['metode'];

    $asal_terpilih = $asal;
    $tujuan_terpilih = $tujuan;
    $berat_terisi = $_POST['berat'];
    $metode_terpilih = $metode;

    if($berat <= 0) {
        $pesan = "Berat harus lebih dari 0.";
    } else {
        $query = mysqli_query($conn, 
            "SELECT * FROM tabel_tarif 
             WHERE kecamatan_asal='$asal' 
             AND kecamatan_tujuan='$tujuan'");
        $data = mysqli_fetch_assoc($query);

        if($data) {
            $total = $berat * $data['harga_per_kg'];
            $kode_resi = "SK" . date("YmdHis") . rand(10,99);

            mysqli_query($conn, "INSERT INTO tabel_pesanan 
            (kode_resi, nama_pengirim, no_hp_pengirim, alamat_pengirim,
            nama_penerima, no_hp_penerima, alamat_penerima,
            berat, total_harga, metode_pembayaran)
            VALUES
            ('$kode_resi', '$nama_pengirim', '$hp_pengirim', '$alamat_pengirim',
            '$nama_penerima', '$hp_penerima', '$alamat_penerima',
            '$berat', '$total', '$metode')");

            $pesan = "Pesanan berhasil dibuat! Kode Resi Anda: <b>$kode_resi</b>";

            $asal_terpilih = "";
            $tujuan_terpilih = "";
            $berat_terisi = "";
            $metode_terpilih = "COD";
        } else {
            $pesan = "Tarif tidak ditemukan untuk wilayah yang dipilih.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Buat Pesanan</h2>
    <a href="dashboard.php">Kembali ke Beranda</a>

    <hr>

    <form method="POST">
        <h3>Data Pengirim</h3>
        <input type="text" name="nama_pengirim" placeholder="Nama Pengirim" required>
        <input type="text" name="hp_pengirim" placeholder="No HP Pengirim" required>
        <textarea name="alamat_pengirim" placeholder="Alamat Pengirim" required></textarea>

        <h3>Data Penerima</h3>
        <input type="text" name="nama_penerima" placeholder="Nama Penerima" required>
        <input type="text" name="hp_penerima" placeholder="No HP Penerima" required>
        <textarea name="alamat_penerima" placeholder="Alamat Penerima" required></textarea>

        <h3>Detail Pengiriman</h3>

        <label>Kecamatan Asal</label>
        <select name="asal" required>
            <option value="">-- Pilih Kecamatan Asal --</option>
            <?php
            mysqli_data_seek($asal_list, 0);
            while($row = mysqli_fetch_assoc($asal_list)) {
                $selected = ($asal_terpilih == $row['kecamatan_asal']) ? "selected" : "";
                echo "<option value='".$row['kecamatan_asal']."' $selected>".$row['kecamatan_asal']."</option>";
            }
            ?>
        </select>

        <label>Kecamatan Tujuan</label>
        <select name="tujuan" required>
            <option value="">-- Pilih Kecamatan Tujuan --</option>
            <?php
            mysqli_data_seek($tujuan_list, 0);
            while($row = mysqli_fetch_assoc($tujuan_list)) {
                $selected = ($tujuan_terpilih == $row['kecamatan_tujuan']) ? "selected" : "";
                echo "<option value='".$row['kecamatan_tujuan']."' $selected>".$row['kecamatan_tujuan']."</option>";
            }
            ?>
        </select>

        <input type="number" name="berat" placeholder="Berat (kg)" step="0.01" min="1" value="<?php echo $berat_terisi; ?>" required>

        <select name="metode" required>
            <option value="COD" <?php echo ($metode_terpilih == "COD") ? "selected" : ""; ?>>Bayar Tunai ke Kurir (COD)</option>
            <option value="TRANSFER" <?php echo ($metode_terpilih == "TRANSFER") ? "selected" : ""; ?>>Transfer Bank</option>
        </select>

        <button type="submit" name="buat">Buat Pesanan</button>
    </form>

    <?php if($pesan != "") { ?>
        <div class="<?php echo (strpos($pesan, 'berhasil') !== false) ? 'success-box' : 'error-box'; ?>">
            <?php echo $pesan; ?>
        </div>
    <?php } ?>

</div>
</body>
</html>