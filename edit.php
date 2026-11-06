
<?php
require 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    exit('ID produk tidak valid.');
}

$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id_produk, nama_produk, id_kategori, harga, stok
     FROM produk WHERE id_produk = ?'
);

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($hasil);

if (!$produk) {
    exit('Produk tidak ditemukan.');
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : '';
    $kategori = isset($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : 0;
    $harga = isset($_POST['harga']) ? (float) $_POST['harga'] : 0;
    $stok = isset($_POST['stok']) ? (int) $_POST['stok'] : 0;

    if ($nama == '' || $kategori <= 0 || $harga <= 0 || $stok < 0) {
        $pesan = 'Data tidak valid. Periksa kembali isian.';
    } else {
        $update = mysqli_prepare(
            $koneksi,
            'UPDATE produk
             SET nama_produk = ?, id_kategori = ?, harga = ?, stok = ?
             WHERE id_produk = ?'
        );

        mysqli_stmt_bind_param(
            $update,
            'sidii',
            $nama,
            $kategori,
            $harga,
            $stok,
            $id
        );

        mysqli_stmt_execute($update);

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
    <title>Edit Produk</title>
</head>
<body>

<h1>Edit Produk</h1>

<?php if ($pesan != ''): ?>
    <p><?php echo e($pesan); ?></p>
<?php endif; ?>

<form method="post">

    <p>
        <label>Nama Produk</label><br>
        <input type="text" name="nama_produk"
               value="<?php echo e($produk['nama_produk']); ?>" required>
    </p>

    <p>
        <label>Kategori</label><br>
        <select name="id_kategori" required>
            <?php while ($baris = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?php echo e($baris['id_kategori']); ?>"
                    <?php
                    if ((int) $baris['id_kategori'] === (int) $produk['id_kategori']) {
                        echo 'selected';
                    }
                    ?>>
                    <?php echo e($baris['nama_kategori']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <label>Harga</label><br>
        <input type="number" name="harga" min="1"
               value="<?php echo e($produk['harga']); ?>" required>
    </p>

    <p>
        <label>Stok</label><br>
        <input type="number" name="stok" min="0"
               value="<?php echo e($produk['stok']); ?>" required>
    </p>

    <button type="submit">Simpan Perubahan</button>

</form>

<p><a href="index.php">Kembali</a></p>

</body>
</html>
