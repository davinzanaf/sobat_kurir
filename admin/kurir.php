<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Tambah Kurir
if(isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_hp = $_POST['no_hp'];

    mysqli_query($conn, "INSERT INTO tabel_users 
        (nama_lengkap, email, password, no_hp, role)
        VALUES 
        ('$nama', '$email', '$password', '$no_hp', 'kurir')");
}

// Hapus Kurir
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tabel_users WHERE id_user='$id' AND role='kurir'");
}
?>

<h2>Manajemen Kurir</h2>
<a href="dashboard.php">Kembali ke Dashboard</a>
<br><br>

<h3>Tambah Kurir</h3>
<form method="POST">
    <input type="text" name="nama" placeholder="Nama Lengkap" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="text" name="no_hp" placeholder="No HP" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="tambah">Tambah Kurir</button>
</form>

<hr>

<h3>Daftar Kurir</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Nama</th>
    <th>Email</th>
    <th>No HP</th>
    <th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM tabel_users WHERE role='kurir'");

while($row = mysqli_fetch_assoc($data)) {
    echo "<tr>
            <td>{$row['nama_lengkap']}</td>
            <td>{$row['email']}</td>
            <td>{$row['no_hp']}</td>
            <td>
                <a href='?hapus={$row['id_user']}' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
            </td>
          </tr>";
}
?>

</table>