<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: login.php");
include '../config/koneksi.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'] ?? '';

$cek = $conn->query("SELECT * FROM user WHERE username = '$username'");
if ($cek->num_rows > 0) {
    echo "<script>alert('Username sudah ada');window.history.back();</script>";
    exit;
}

$conn->query("INSERT INTO user (username, password, role) VALUES ('$username', '$password', 'karyawan')");
$user_id = $conn->insert_id;
$conn->query("INSERT INTO karyawan (user_id, nama, jabatan) VALUES ($user_id, '$nama', '$jabatan')");

header("Location: ../admin/?page=karyawan");
