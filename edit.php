<?php
require __DIR__ . '/config.php';
wajib_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { flash('ID perangkat tidak valid.', 'error'); header('Location: perangkat.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM perangkat WHERE id = ?');
$stmt->execute([$id]);
$existing = $stmt->fetch();
if (!$existing) { flash('Data perangkat tidak ditemukan.', 'error'); header('Location: perangkat.php'); exit; }

$data = $existing;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['kode','nama','kategori','merek','jumlah','kondisi','lokasi','tanggal_masuk'] as $key) {
        $data[$key] = trim((string)($_POST[$key] ?? ''));
    }
    if ($data['kode'] === '') $errors[] = 'Kode perangkat wajib diisi.';
    if ($data['nama'] === '') $errors[] = 'Nama perangkat wajib diisi.';
    if ($data['kategori'] === '') $errors[] = 'Kategori wajib dipilih.';
    if ($data['merek'] === '') $errors[] = 'Merek wajib diisi.';
    if (!filter_var($data['jumlah'], FILTER_VALIDATE_INT) || (int)$data['jumlah'] < 0) $errors[] = 'Jumlah harus berupa angka nol atau lebih.';
    if (!in_array($data['kondisi'], ['Baik','Perlu Perbaikan','Rusak'], true)) $errors[] = 'Kondisi tidak valid.';
    if ($data['lokasi'] === '') $errors[] = 'Lokasi wajib diisi.';
    $date = DateTime::createFromFormat('Y-m-d', $data['tanggal_masuk']);
    if (!$date || $date->format('Y-m-d') !== $data['tanggal_masuk']) $errors[] = 'Tanggal masuk tidak valid.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('UPDATE perangkat SET kode=?, nama=?, kategori=?, merek=?, jumlah=?, kondisi=?, lokasi=?, tanggal_masuk=? WHERE id=?');
            $stmt->execute([$data['kode'],$data['nama'],$data['kategori'],$data['merek'],(int)$data['jumlah'],$data['kondisi'],$data['lokasi'],$data['tanggal_masuk'],$id]);
            flash('Data perangkat berhasil diperbarui.');
            header('Location: perangkat.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = $e->getCode() === '23000' ? 'Kode perangkat sudah digunakan oleh data lain.' : 'Perubahan gagal disimpan. Periksa kembali isian.';
        }
    }
}
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Perangkat | InventarisKu</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="app-shell">
<aside class="sidebar"><a class="brand" href="index.php"><span class="brand-mark small">IK</span><span>Inventaris<span class="brand-light">Ku</span></span></a><p class="nav-label">MENU UTAMA</p><a class="nav-link" href="index.php"><span>▦</span> Dashboard</a><a class="nav-link active" href="perangkat.php"><span>▤</span> Data Perangkat</a><div class="sidebar-bottom"><div class="profile-chip"><div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A',0,1))) ?></div><div><strong><?= e($_SESSION['admin_username'] ?? 'Admin') ?></strong><small>Administrator</small></div></div><a class="nav-link logout-link" href="logout.php"><span>↪</span> Keluar</a></div></aside>
<main class="main-content"><header class="topbar"><div><p class="eyebrow">DATA PERANGKAT</p><h1>Edit perangkat</h1></div><a class="btn secondary" href="perangkat.php">← Kembali</a></header>
<section class="panel form-panel"><div class="panel-heading"><div><h2>Perbarui informasi perangkat</h2><p>Ubah data yang diperlukan, lalu simpan perubahan.</p></div></div>
<?php if ($errors): ?><div class="alert error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="data-form">
  <label>Kode perangkat<input name="kode" value="<?= e($data['kode']) ?>" required></label>
  <label>Nama perangkat<input name="nama" value="<?= e($data['nama']) ?>" required></label>
  <label>Kategori<select name="kategori" required><?php foreach (['Komputer','Monitor','Aksesori','Printer','Jaringan','Lainnya'] as $v): ?><option <?= $data['kategori']===$v?'selected':'' ?> value="<?= e($v) ?>"><?= e($v) ?></option><?php endforeach; ?></select></label>
  <label>Merek<input name="merek" value="<?= e($data['merek']) ?>" required></label>
  <label>Jumlah<input type="number" min="0" name="jumlah" value="<?= e((string)$data['jumlah']) ?>" required></label>
  <label>Kondisi<select name="kondisi"><?php foreach (['Baik','Perlu Perbaikan','Rusak'] as $v): ?><option <?= $data['kondisi']===$v?'selected':'' ?> value="<?= e($v) ?>"><?= e($v) ?></option><?php endforeach; ?></select></label>
  <label>Lokasi<input name="lokasi" value="<?= e($data['lokasi']) ?>" required></label>
  <label>Tanggal masuk<input type="date" name="tanggal_masuk" value="<?= e($data['tanggal_masuk']) ?>" required></label>
  <div class="form-actions"><a class="btn ghost" href="perangkat.php">Batal</a><button class="btn primary" type="submit">Simpan perubahan</button></div>
</form></section><footer class="footer">InventarisKu · Project CRUD RPL</footer></main></div></body></html>
