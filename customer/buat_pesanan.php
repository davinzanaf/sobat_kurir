<?php
include "../config/koneksi.php";

$pesan = "";

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
    } else {
        $pesan = "Tarif tidak ditemukan.";
    }
}
}
?>

<h2>Buat Pesanan</h2>

<form method="POST">
    <h3>Data Pengirim</h3>
    <input type="text" name="nama_pengirim" placeholder="Nama Pengirim" required><br><br>
    <input type="text" name="hp_pengirim" placeholder="No HP Pengirim" required><br><br>
    <textarea name="alamat_pengirim" placeholder="Alamat Pengirim" required></textarea><br><br>

    <h3>Data Penerima</h3>
    <input type="text" name="nama_penerima" placeholder="Nama Penerima" required><br><br>
    <input type="text" name="hp_penerima" placeholder="No HP Penerima" required><br><br>
    <textarea name="alamat_penerima" placeholder="Alamat Penerima" required></textarea><br><br>

    <h3>Detail Pengiriman</h3>
    <input type="text" name="asal" placeholder="Kecamatan Asal" required><br><br>
    <input type="text" name="tujuan" placeholder="Kecamatan Tujuan" required><br><br>
    <input type="number" name="berat" placeholder="Berat (kg)" step="0.01" min="1" required><br><br>

    <select name="metode" required>
        <option value="COD">Bayar Tunai ke Kurir (COD)</option>
        <option value="TRANSFER">Transfer Bank</option>
    </select><br><br>

    <button type="submit" name="buat">Buat Pesanan</button>
</form>

<br>

<?php echo $pesan; ?>