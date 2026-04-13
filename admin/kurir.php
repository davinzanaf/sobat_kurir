<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

/* TAMBAH KURIR */
if(isset($_POST['simpan'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'kurir';

    mysqli_query($conn, "INSERT INTO tabel_users (nama_lengkap, email, password, no_hp, role)
                         VALUES ('$nama_lengkap', '$email', '$password', '$no_hp', '$role')");

    header("Location: kurir.php");
    exit;
}

/* HAPUS KURIR */
if(isset($_GET['hapus'])) {
    $id_user = (int) $_GET['hapus'];

    // Lepaskan dulu relasi pesanan ke kurir ini
    mysqli_query($conn, "UPDATE tabel_pesanan SET id_kurir = NULL WHERE id_kurir='$id_user'");

    // Baru hapus kurir
    mysqli_query($conn, "DELETE FROM tabel_users WHERE id_user='$id_user' AND role='kurir'");

    header("Location: kurir.php");
    exit;
}

/* TAMPIL DATA KURIR */
$data_kurir = mysqli_query($conn, "SELECT * FROM tabel_users WHERE role='kurir' ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kurir</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Kelola Kurir</h2>
    <p>Selamat datang, <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; ?></p>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    <a href="logout.php">Logout</a>

    <hr>

    <h3>Tambah Kurir</h3>

    <form method="POST">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>No HP</label>
        <input type="text" name="no_hp" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="simpan">Simpan Kurir</button>
    </form>

    <hr>

    <h3>Data Kurir</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Role</th>
            <th>Dibuat</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        if(mysqli_num_rows($data_kurir) > 0) {
            while($row = mysqli_fetch_assoc($data_kurir)) {
                echo "<tr>
                        <td>$no</td>
                        <td>{$row['nama_lengkap']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['no_hp']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a class='btn' href='kurir.php?hapus={$row['id_user']}'
                               onclick=\"return confirm('Yakin ingin menghapus kurir ini?')\">Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7'>Belum ada data kurir</td></tr>";
        }
        ?>
    </table>

</div>
</body>
</html>