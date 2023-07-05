<?php
session_start();
$koneksi = mysqli_connect('localhost', 'root', '', 'testphpmailer');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $koneksi->query("SELECT * FROM data WHERE email = '{$email}' AND password = '{$password}'");
    $check = $query->num_rows;

    if ($check > 0) { // Cek apakah user ada di database(?)
        $verif = $query->fetch_assoc();
        if ($verif['is_verif'] == 1) { // Cek apakah user sudah verifikasi(?)
            $_SESSION['user'] = $verif;
            echo "<script>alert('Login berhasil!'); window.location = 'index.php'; </script>";
        } else {
            echo "<script>alert('Harap verifikasi akun Anda!'); window.location = 'login.php'; </script>";
        }
    } else {
        echo "<script>alert('Email atau password salah!'); window.location = 'index.php'; </script>";
    }
}
?>

<form action="" method="post">
    <div>
        <label for="">Email</label>
        <input type="email" name="email">
    </div>
    <div>
        <label for="">Password</label>
        <input type="password" name="password">
    </div>

    <button type="submit" name="login">Login</button>
    <p>Belum memiliki akun? <a href="register.php">Daftar!</a></p>
</form>