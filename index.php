<?php
session_start();
// Cek session
if (!$_SESSION['user']) {         
    header('location: login.php');
}
?>

<h3>Login dan Verifikasi Berhasil!</h3>

<form action="logout.php" method="post">
    <button type="submit" name="logout">Logout</button>
</form>