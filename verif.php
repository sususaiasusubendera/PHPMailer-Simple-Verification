<?php
$koneksi = mysqli_connect('localhost', 'root', '', 'testphpmailer');

$code = $_GET['code'];

if (isset($code)) {
    $query = $koneksi->query("SELECT * FROM data WHERE kode_verif = '{$code}'");
    $result = $query->fetch_assoc();

    $koneksi->query("UPDATE data SET is_verif = 1 WHERE id = '" . $result['id'] . "'");
    echo "<script>alert('Verifikasi berhasil, silahkan login'); window.location = 'login.php'</script>";
}
