
<?php
require 'koneksi.php';

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : '';
    $kategori = isset($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : 0;
    $harga = isset($_POST['harga']) ? (float) $_POST['harga'] : 0;
    $stok = isset($_POST['stok']) ? (int) $_POST['stok'] : 0;

    if ($nama == '' || $kategori <= 0 || $harga <= 0 || $stok < 0) {
        $pesan = 'Data tidak valid. Periksa kembali isian.';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            'INSERT INTO produk (nama_produk, id_kategori, harga, stok)
             VALUES (?, ?, ?, ?)'
        );

        mysqli_stmt_bind_param($stmt, 'sidi', $nama, $kategori, $harga, $stok);
        mysqli_stmt_execute($stmt);

        header('Location: index.php');
        exit;
    }
}

$kategori = mysqli_query(
    $koneksi,
    'SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori'
);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Produk</title>
</head>
<body>

<h1>Tambah Produk</h1>

<?php if ($pesan != ''): ?>
    <p><?php echo e($pesan); ?></p>
<?php endif; ?>

<form method="post">
    <p>
        <label>Nama Produk</label><br>
        <input type="text" name="nama_produk" required>
    </p>

    <p>
        <label>Kategori</label><br>
        <select name="id_kategori" required>
            <option value="">Pilih kategori</option>
            <?php while ($baris = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?php echo e($baris['id_kategori']); ?>">
                    <?php echo e($baris['nama_kategori']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <label>Harga</label><br>
        <input type="number" name="harga" min="1" required>
    </p>

    <p>
        <label>Stok</label><br>
        <input type="number" name="stok" min="0" required>
    </p>

    <button type="submit">Simpan</button>
</form>

<p><a href="index.php">Kembali</a></p>

</body>
</html>
