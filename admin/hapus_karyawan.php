<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: login.php");
include '../config/koneksi.php';

$id = $_GET['id'];
$karyawan = $conn->query("SELECT user_id FROM karyawan WHERE id = $id")->fetch_assoc();

if ($karyawan) {
    $user_id = $karyawan['user_id'];
    $conn->query("DELETE FROM user WHERE id = $user_id");
}

header("Location: ../admin/?page=karyawan");
