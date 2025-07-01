<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Sistem Cuti</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 0.8s linear infinite;
    }
    .checkmark {
      font-size: 40px;
      color: #10B981;
      animation: pop 0.4s ease;
    }
    .crossmark {
      font-size: 40px;
      color: #EF4444;
      animation: pop 0.4s ease;
    }
    @keyframes pop {
      0% { transform: scale(0); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

  <!-- Fullscreen Loader -->
  <div id="fullscreenLoader" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="loaderContent" class="bg-white p-6 rounded shadow-md text-center transition">
      <div id="spinner" class="spinner mx-auto mb-4"></div>
      <div id="checkmark" class="checkmark mb-4 hidden">✅</div>
      <div id="crossmark" class="crossmark mb-4 hidden">❌</div>
      <p id="loaderText" class="text-lg font-semibold text-gray-700">Sedang memproses login...</p>
    </div>
  </div>

  <!-- Login Form -->
  <form id="loginForm" class="bg-white p-8 rounded shadow-md w-96 z-10">
    <h2 class="text-2xl mb-6 text-center font-semibold">Login Sistem Cuti</h2>
    <input type="text" name="username" placeholder="Username" class="w-full mb-4 p-2 border rounded" required>
    <input type="password" name="password" placeholder="Password" class="w-full mb-4 p-2 border rounded" required>
    <button id="loginBtn" class="bg-blue-600 hover:bg-blue-700 text-white w-full p-2 rounded" type="submit">
      Login
    </button>
  </form>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const loader = document.getElementById('fullscreenLoader');
      const spinner = document.getElementById('spinner');
      const checkmark = document.getElementById('checkmark');
      const crossmark = document.getElementById('crossmark');
      const loaderText = document.getElementById('loaderText');

      // Reset tampilan
      spinner.classList.remove('hidden');
      checkmark.classList.add('hidden');
      crossmark.classList.add('hidden');
      loaderText.textContent = "Sedang memproses login...";
      loader.classList.remove('hidden');

      const formData = new FormData(this);

      fetch('proses_login.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          // Simulasi loading 2 detik
          setTimeout(() => {
            spinner.classList.add('hidden');
            checkmark.classList.remove('hidden');
            loaderText.textContent = "Login Berhasil!";
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1000);
          }, 2000);
        } else {
          // Simulasi gagal 2 detik
          setTimeout(() => {
            spinner.classList.add('hidden');
            crossmark.classList.remove('hidden');
            loaderText.textContent = "Login Gagal!";
            setTimeout(() => {
              loader.classList.add('hidden');
            }, 1500);
          }, 2000);
        }
      })
      .catch(err => {
        alert('Terjadi kesalahan saat login.');
        console.error(err);
        loader.classList.add('hidden');
      });
    });
  </script>
</body>
</html>
