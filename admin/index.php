<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: login.php");

include '../config/koneksi.php';

$page = $_GET['page'] ?? 'permohonan';
$page = $_GET['page'] ?? 'dashboard';

// Ambil data berdasarkan halaman
if ($page === 'karyawan') {
    $result = $conn->query("SELECT k.id, u.username, k.nama, k.jabatan FROM karyawan k JOIN user u ON u.id = k.user_id");
} else {
    $result = $conn->query("
        SELECT p.*, k.nama 
        FROM permohonan p
        JOIN karyawan k ON p.karyawan_id = k.id
        ORDER BY p.status ASC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Dashboard Admin</title>
</head>
<body class="flex">

  <!-- Sidebar -->
  <div class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <h2 class="text-xl font-bold mb-6">Admin Panel</h2>
    <ul>
      <li class="mb2">
         <a href="?page=dashboard" class="block p-2 rounded hover:bg-gray-700 <?= $page=='dashboard'?'bg-gray-700':'' ?>">Dasboard</a>
      </li>
      <li class="mb-2">
        <a href="?page=permohonan" class="block p-2 rounded hover:bg-gray-700 <?= $page=='permohonan'?'bg-gray-700':'' ?>">Permohonan Cuti</a>
      </li>
      <li class="mb-2">
        <a href="?page=karyawan" class="block p-2 rounded hover:bg-gray-700 <?= $page=='karyawan'?'bg-gray-700':'' ?>">Data Karyawan</a>
      </li>

      <li class="mt-6">
        <a href="../logout.php" onclick="return confirm('Apakah yakin?');" class="text-red-400 hover:text-red-200">Logout</a>
      </li>
    </ul>
  </div>

  <!-- Content -->
  <div class="flex-1 p-6">
<?php if ($page === 'dashboard'): ?>
  <?php
   $totalKaryawan = $conn->query("SELECT COUNT(*) as total FROM karyawan")->fetch_assoc()['total'];
  $totalPending = $conn->query("SELECT COUNT(*) as total FROM permohonan WHERE status = 'pending'")->fetch_assoc()['total'];
  $totalDisetujui = $conn->query("SELECT COUNT(*) as total FROM permohonan WHERE status = 'disetujui'")->fetch_assoc()['total'];
  $totalDitolak = $conn->query("SELECT COUNT(*) as total FROM permohonan WHERE status = 'ditolak'")->fetch_assoc()['total'];
  $totalCuti = $conn->query("SELECT COUNT(*) as total FROM permohonan")->fetch_assoc()['total'];

  ?>
  <h1 class="text-2xl mb-6">Dashboard Admin</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
  <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
    <h3 class="text-gray-700 text-lg">Total Karyawan</h3>
    <p class="text-3xl font-bold text-blue-600"><?= $totalKaryawan ?></p>
  </div>
  <div class="bg-white p-6 rounded shadow border-l-4 border-yellow-500">
    <h3 class="text-gray-700 text-lg">Pending</h3>
    <p class="text-3xl font-bold text-yellow-600"><?= $totalPending ?></p>
  </div>
  <div class="bg-white p-6 rounded shadow border-l-4 border-green-500">
    <h3 class="text-gray-700 text-lg">Disetujui</h3>
    <p class="text-3xl font-bold text-green-600"><?= $totalDisetujui ?></p>
  </div>
  <div class="bg-white p-6 rounded shadow border-l-4 border-red-500">
    <h3 class="text-gray-700 text-lg">Ditolak</h3>
    <p class="text-3xl font-bold text-red-600"><?= $totalDitolak ?></p>
  </div>
  <div class="bg-white p-6 rounded shadow border-l-4 border-gray-500">
    <h3 class="text-gray-700 text-lg">Total Permohonan</h3>
    <p class="text-3xl font-bold text-gray-700"><?= $totalCuti ?></p>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="bg-white p-4 rounded shadow">
    <h3 class="text-gray-700 mb-2">Pie Chart Status Cuti</h3>
    <canvas id="pieChart"></canvas>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h3 class="text-gray-700 mb-2">Bar Chart Statistik</h3>
    <canvas id="barChart"></canvas>
  </div>
</div>


<?php endif; ?>
    <?php if ($page === 'karyawan'): ?>
      <h1 class="text-2xl mb-4">Data Karyawan</h1>
      <form action="tambah_karyawan.php" method="POST" class="mb-4 bg-white p-4 shadow rounded max-w-xl">
        <h2 class="text-xl mb-2">Tambah Karyawan Baru</h2>
        <div class="mb-2">
          <input type="text" name="username" placeholder="Username" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-2">
          <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-2">
          <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-2">
          <input type="text" name="jabatan" placeholder="Jabatan" class="w-full border p-2 rounded">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Tambah</button>
      </form>
      

      <table class="w-full border-collapse bg-white shadow">
        <thead>
          <tr class="bg-gray-200">
            <th class="border p-2">Username</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Jabatan</th>
            <th class="border p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="border p-2"><?= $row['username'] ?></td>
              <td class="border p-2"><?= $row['nama'] ?></td>
              <td class="border p-2"><?= $row['jabatan'] ?></td>
              <td class="border p-2">
                <a href="edit_karyawan.php?id=<?= $row['id'] ?>" class="text-blue-600">Edit</a> |
                <a href="hapus_karyawan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')" class="text-red-600">Hapus</a>
                </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php elseif ($page === 'permohonan'): ?>
      <h1 class="text-2xl mb-4">Permohonan Cuti</h1>
      <input type="text" id="searchInput" placeholder="Cari nama atau alasan..." class="mb-4 p-2 border rounded w-full">
      <table class="w-full table-auto border-collapse bg-white shadow">
        <thead>
          <tr class="bg-gray-200">
            <th class="border p-2">Nama</th>
            <th class="border p-2">Mulai</th>
            <th class="border p-2">Selesai</th>
            <th class="border p-2">Alasan</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="border p-2"><?= $row['nama'] ?></td>
              <td class="border p-2"><?= $row['tanggal_mulai'] ?></td>
              <td class="border p-2"><?= $row['tanggal_selesai'] ?></td>
              <td class="border p-2"><?= $row['alasan'] ?></td>
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
              <td class="border p-2">
                <a href="proses_approve.php?id=<?= $row['id'] ?>&aksi=setuju" class="text-green-600 hover:underline mr-2">Setujui</a>
                <a href="proses_approve.php?id=<?= $row['id'] ?>&aksi=tolak" class="text-yellow-600 hover:underline mr-2">Tolak</a>
                <a href="hapus_cuti.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:underline">Hapus</a>
                </td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function () {
    $("#searchInput").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#cutiTable tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const pieCtx = document.getElementById('pieChart').getContext('2d');
const barCtx = document.getElementById('barChart').getContext('2d');

new Chart(pieCtx, {
  type: 'pie',
  data: {
    labels: ['Pending', 'Disetujui', 'Ditolak'],
    datasets: [{
      label: 'Status Permohonan',
      data: [<?= $totalPending ?>, <?= $totalDisetujui ?>, <?= $totalDitolak ?>],
      backgroundColor: ['#facc15', '#22c55e', '#ef4444']
    }]
  }
});

new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Karyawan', 'Total Cuti', 'Pending', 'Disetujui', 'Ditolak'],
    datasets: [{
      label: 'Jumlah',
      data: [<?= $totalKaryawan ?>, <?= $totalCuti ?>, <?= $totalPending ?>, <?= $totalDisetujui ?>, <?= $totalDitolak ?>],
      backgroundColor: [
        '#3b82f6', '#64748b',
        '#facc15', '#22c55e', '#ef4444'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>


</html>
