<?php
include '../config/koneksi.php';

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'setuju') {
    $conn->query("UPDATE permohonan SET status = 'disetujui' WHERE id = $id");
} else {
    $conn->query("UPDATE permohonan SET status = 'ditolak' WHERE id = $id");
}
header("Location: ../admin/?page=permohonan");
