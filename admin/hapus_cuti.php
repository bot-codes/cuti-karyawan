<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM permohonan WHERE id = $id");
}

header("Location: ../admin/?page=permohonan");
exit;
?>
