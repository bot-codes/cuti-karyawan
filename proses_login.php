<?php
session_start();
include 'config/koneksi.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$query = $conn->prepare("SELECT * FROM user WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['role'] = $data['role'];

    $redirect = ($data['role'] === 'admin') ? 'admin' : 'karyawan';
    echo json_encode([
        'status' => 'success',
        'redirect' => $redirect
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Login gagal. Username atau password salah.'
    ]);
}
