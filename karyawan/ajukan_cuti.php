<?php
session_start();
if ($_SESSION['role'] != 'karyawan') header("Location: login.php");
include '../config/koneksi.php';

$user_id = $_SESSION['user_id'];
$karyawan = $conn->query("SELECT * FROM karyawan WHERE user_id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mulai   = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $alasan  = $conn->real_escape_string($_POST['alasan']);

    $conn->query("INSERT INTO permohonan (karyawan_id, tanggal_mulai, tanggal_selesai, alasan) 
                  VALUES ({$karyawan['id']}, '$mulai', '$selesai', '$alasan')");
    header("Location: dashboard_karyawan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ajukan Cuti</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white min-h-screen p-4">
  <h2 class="text-xl font-bold mb-6">Karyawan</h2>
  <ul>
    <li class="mb-2">
      <a href="dashboard_karyawan.php" class="block p-2 rounded hover:bg-gray-700">Dashboard</a>
    </li>
    <li class="mb-2">
      <a href="ajukan_cuti.php" class="block p-2 rounded bg-gray-700">Ajukan Cuti</a>
    </li>
    <li class="mt-6">
      <a href="logout.php" onclick="return confirm('Yakin ingin logout?');" class="text-red-400 hover:text-red-200">Logout</a>
    </li>
  </ul>
</div>

<!-- Form -->
<div class="flex-1 p-6 bg-gray-50 min-h-screen">
  <h1 class="text-2xl font-semibold mb-6">Form Pengajuan Cuti</h1>
  <form method="POST" action="" class="bg-white p-6 rounded shadow-md max-w-xl">
    <div class="mb-4">
      <label class="block mb-1">Tanggal Mulai</label>
      <input type="date" name="tanggal_mulai" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
      <label class="block mb-1">Tanggal Selesai</label>
      <input type="date" name="tanggal_selesai" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
      <label class="block mb-1">Alasan</label>
      <textarea name="alasan" rows="3" required class="w-full border p-2 rounded"></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Pengajuan</button>
  </form>
</div>
</body>
</html>
