<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: login.php");

include '../config/koneksi.php';

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$password = $_POST['password'];
$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'];

if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $conn->query("UPDATE user SET username = '$username', password = '$hash' WHERE id = $user_id");
} else {
    $conn->query("UPDATE user SET username = '$username' WHERE id = $user_id");
}

$conn->query("UPDATE karyawan SET nama = '$nama', jabatan = '$jabatan' WHERE id = $id");

header("Location: ../admin/?page=permohonan");
