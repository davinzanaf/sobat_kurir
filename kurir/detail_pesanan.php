<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) {
    echo "ID pesanan tidak ditemukan";
    exit;
}

$id_pesanan = (int) $_GET['id'];
$id_kurir = $_SESSION['id_user'];

$pesanan = mysqli_query($conn, "SELECT * FROM tabel_pesanan 
                                WHERE id_pesanan='$id_pesanan' 
                                AND id_kurir='$id_kurir'");

if(mysqli_num_rows($pesanan) == 0) {
    echo "Pesanan tidak ditemukan atau bukan milik Anda";
    exit;
}

$data = mysqli_fetch_assoc($pesanan);

$tracking = mysqli_query($conn, "SELECT * FROM tabel_tracking 
                                 WHERE id_pesanan='$id_pesanan'
                                 ORDER BY id_tracking DESC");
?>

<h2>Detail Pesanan</h2>
<a href="dashboard.php">Kembali ke Dashboard</a>
<hr>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <td><b>Kode Resi</b></td>
        <td><?php echo $data['kode_resi']; ?></td>
    </tr>
    <tr>
        <td><b>Nama Pengirim</b></td>
        <td><?php echo $data['nama_pengirim']; ?></td>
    </tr>
    <tr>
        <td><b>No HP Pengirim</b></td>
        <td><?php echo $data['no_hp_pengirim']; ?></td>
    </tr>
    <tr>
        <td><b>Alamat Pengirim</b></td>
        <td><?php echo $data['alamat_pengirim']; ?></td>
    </tr>
    <tr>
        <td><b>Nama Penerima</b></td>
        <td><?php echo $data['nama_penerima']; ?></td>
    </tr>
    <tr>
        <td><b>No HP Penerima</b></td>
        <td><?php echo $data['no_hp_penerima']; ?></td>
    </tr>
    <tr>
        <td><b>Alamat Penerima</b></td>
        <td><?php echo $data['alamat_penerima']; ?></td>
    </tr>
    <tr>
        <td><b>Berat</b></td>
        <td><?php echo $data['berat']; ?> Kg</td>
    </tr>
    <tr>
        <td><b>Total Harga</b></td>
        <td>Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td><b>Metode Pembayaran</b></td>
        <td><?php echo $data['metode_pembayaran']; ?></td>
    </tr>
    <tr>
        <td><b>Status Pesanan</b></td>
        <td><?php echo $data['status_pesanan']; ?></td>
    </tr>
</table>
    <br>

    <?php if($data['status_pesanan'] == 'DIJEMPUT') { ?>
        <a href="update_status.php?id=<?php echo $data['id_pesanan']; ?>&aksi=kirim"
        onclick="return confirm('Yakin pesanan ini sedang dikirim?')">
        Kirim Pesanan
        </a>
    <?php } ?>

    <?php if($data['status_pesanan'] == 'DALAM_PENGIRIMAN') { ?>
        <a href="update_status.php?id=<?php echo $data['id_pesanan']; ?>&aksi=selesai"
        onclick="return confirm('Yakin pesanan ini sudah selesai?')">
        Selesaikan Pesanan
        </a>
    <?php } ?>

    <br><br>
<br>

<h3>Riwayat Tracking</h3>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Keterangan</th>
        <th>Waktu Update</th>
    </tr>

    <?php
    $no = 1;
    if(mysqli_num_rows($tracking) > 0) {
        while($row = mysqli_fetch_assoc($tracking)) {
            echo "<tr>
                    <td>$no</td>
                    <td>{$row['keterangan']}</td>
                    <td>{$row['waktu_update']}</td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='3'>Belum ada riwayat tracking</td></tr>";
    }
    ?>
</table>