<?php
session_start();
if ($_SESSION['role'] != 'karyawan') header("Location: login.php");
include '../config/koneksi.php';

$user_id = $_SESSION['user_id'];
$karyawan = $conn->query("SELECT * FROM karyawan WHERE user_id = $user_id")->fetch_assoc();

// Proses pengajuan cuti
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $alasan = $conn->real_escape_string($_POST['alasan']);

    $conn->query("INSERT INTO permohonan (karyawan_id, tanggal_mulai, tanggal_selesai, alasan) 
                  VALUES ({$karyawan['id']}, '$mulai', '$selesai', '$alasan')");
    header("Location: ../karyawan");
    exit;
}

// Ambil data permohonan cuti
$permohonan = $conn->query("SELECT * FROM permohonan WHERE karyawan_id = {$karyawan['id']} ORDER BY id DESC");

// Hitung status
$jumlah_disetujui = $conn->query("SELECT COUNT(*) as total FROM permohonan WHERE karyawan_id = {$karyawan['id']} AND status='disetujui'")->fetch_assoc()['total'];
$jumlah_pending   = $conn->query("SELECT COUNT(*) as total FROM permohonan WHERE karyawan_id = {$karyawan['id']} AND status='pending'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Karyawan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white min-h-screen p-4">
  <h2 class="text-xl font-bold mb-6">Karyawan</h2>
  <ul>
    <li class="mb-2">
      <a href="#" class="block p-2 rounded hover:bg-gray-700">Dashboard</a>
    </li>
    <li class="mt-6">
      <a href="../logout.php" onclick="return confirm('Apakah yakin ingin keluar?');" class="text-red-400 hover:text-red-200">Logout</a>
    </li>
  </ul>
</div>

<!-- Main Content -->
<div class="flex-1 p-6 bg-gray-50 min-h-screen">
  <h1 class="text-2xl mb-4 font-semibold">Halo, <?= htmlspecialchars($karyawan['nama']) ?></h1>

  <!-- Statistik Card -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Disetujui -->
    <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded shadow">
      <div class="text-green-800 font-semibold text-lg">Sudah Disetujui</div>
      <div class="text-2xl font-bold"><?= $jumlah_disetujui ?></div>
    </div>

    <!-- Pending -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded shadow">
      <div class="text-yellow-800 font-semibold text-lg">Menunggu Persetujuan</div>
      <div class="text-2xl font-bold"><?= $jumlah_pending ?></div>
    </div>
  </div>

  <!-- Tombol Buka Modal -->
  <button onclick="document.getElementById('modal').classList.remove('hidden')" 
          class="bg-green-600 text-white px-4 py-2 rounded mb-4">
    + Ajukan Cuti
  </button>

  <!-- Tabel Riwayat -->
  <h2 class="text-xl mb-2">Riwayat Cuti</h2>
  <table class="w-full mt-2 table-auto border-collapse bg-white shadow">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">Mulai</th>
        <th class="border p-2">Selesai</th>
        <th class="border p-2">Alasan</th>
        <th class="border p-2">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $permohonan->fetch_assoc()): ?>
        <tr>
          <td class="border p-2"><?= $row['tanggal_mulai'] ?></td>
          <td class="border p-2"><?= $row['tanggal_selesai'] ?></td>
          <td class="border p-2"><?= htmlspecialchars($row['alasan']) ?></td>
          <td class="border p-2">
            <?php
              $status = $row['status'];
              $warna = match($status) {
                'pending' => 'bg-orange-100 text-orange-800',
                'disetujui' => 'bg-green-100 text-green-800',
                'ditolak' => 'bg-red-100 text-red-800',
                default => 'bg-gray-100 text-gray-800',
              };
            ?>
            <span class="px-3 py-1 rounded text-sm font-semibold <?= $warna ?>">
              <?= ucfirst($status) ?>
            </span>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal Form Cuti -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-lg rounded-lg p-6 relative">
    <h2 class="text-xl font-bold mb-4">Form Pengajuan Cuti</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label class="block mb-1">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" required class="w-full border p-2 rounded">
      </div>
      <div class="mb-3">
        <label class="block mb-1">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" required class="w-full border p-2 rounded">
      </div>
      <div class="mb-3">
        <label class="block mb-1">Alasan</label>
        <textarea name="alasan" rows="3" required class="w-full border p-2 rounded"></textarea>
      </div>
      <div class="flex justify-between mt-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
        <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" 
                class="text-gray-600 hover:text-black">Batal</button>
      </div>
    </form>
    <button onclick="document.getElementById('modal').classList.add('hidden')"
            class="absolute top-2 right-3 text-gray-400 hover:text-black text-lg">✕</button>
  </div>
</div>

</body>
</html>
