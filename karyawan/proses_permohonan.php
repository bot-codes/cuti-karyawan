<?php
include '../config/koneksi.php';

$karyawan_id = $_POST['karyawan_id'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];
$alasan = $_POST['alasan'];

$conn->query("INSERT INTO permohonan (karyawan_id, tanggal_mulai, tanggal_selesai, alasan) 
              VALUES ('$karyawan_id', '$tanggal_mulai', '$tanggal_selesai', '$alasan')");

header("Location: dashboard_karyawan.php");
