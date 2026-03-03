<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>sobat kurir</h2>
        <p>Silahkan Masuk Ke Akun Anda</p>

        <form action="proses_login.php" method="POST">
            <label>Email Anda</label>
            <input type="email" name="email" placeholder="Contoh: admin@sobatkurir.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password" required>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>