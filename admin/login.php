<?php
session_start();
include "../config/koneksi.php";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM tabel_users WHERE email='$email' AND role='admin'");
    $data = mysqli_fetch_assoc($query);

    if($data && password_verify($password, $data['password'])) {
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['role'] = $data['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
</head>
<body>
    <h2>Login Admin</h2>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>