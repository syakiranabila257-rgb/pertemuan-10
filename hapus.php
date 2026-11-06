
<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Penghapusan hanya boleh dilakukan melalui tombol Hapus.');
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    exit('ID produk tidak valid.');
}

$stmt = mysqli_prepare(
    $koneksi,
    'DELETE FROM produk WHERE id_produk = ?'
);

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) === 0) {
    exit('Produk tidak ditemukan atau sudah dihapus.');
}

header('Location: index.php');
exit;
