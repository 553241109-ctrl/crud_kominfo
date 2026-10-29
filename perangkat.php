<?php
require __DIR__ . '/config.php';
wajib_login();

$q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM perangkat';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE kode LIKE ? OR nama LIKE ? OR kategori LIKE ? OR merek LIKE ? OR lokasi LIKE ?';
    $term = '%' . $q . '%';
    $params = [$term, $term, $term, $term, $term];
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
$flash = ambil_flash();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Perangkat | InventarisKu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
  <aside class="sidebar">
    <a class="brand" href="index.php"><span class="brand-mark small">IK</span><span>Inventaris<span class="brand-light">Ku</span></span></a>
    <p class="nav-label">MENU UTAMA</p>
    <a class="nav-link" href="index.php"><span>▦</span> Dashboard</a>
    <a class="nav-link active" href="perangkat.php"><span>▤</span> Data Perangkat</a>
    <div class="sidebar-bottom">
      <div class="profile-chip"><div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1))) ?></div><div><strong><?= e($_SESSION['admin_username'] ?? 'Admin') ?></strong><small>Administrator</small></div></div>
      <a class="nav-link logout-link" href="logout.php"><span>↪</span> Keluar</a>
    </div>
  </aside>
  <main class="main-content">
    <header class="topbar"><div><p class="eyebrow">INVENTARIS</p><h1>Data Perangkat</h1></div><div class="topbar-right"><span class="status-dot"></span> Sistem aktif <div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1))) ?></div></div></header>
    <?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <section class="panel">
      <div class="panel-heading"><div><h2>Daftar perangkat komputer</h2><p>Tambah dan kelola data inventaris perangkat.</p></div><a class="btn primary" href="tambah.php">＋ Tambah perangkat</a></div>
      <form method="get" class="search-row"><div class="search-box"><span>⌕</span><input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari kode, nama, merek, atau lokasi..."></div><button class="btn secondary" type="submit">Cari</button><?php if ($q !== ''): ?><a class="btn ghost" href="perangkat.php">Reset</a><?php endif; ?></form>
      <div class="table-meta">Menampilkan <strong><?= count($items) ?></strong> data perangkat</div>
      <?php if (!$items): ?><div class="empty-state">Data tidak ditemukan. Coba kata kunci lain atau tambahkan perangkat baru.</div><?php else: ?>
      <div class="table-wrap"><table><thead><tr><th>Perangkat</th><th>Kategori</th><th>Merek</th><th>Jumlah</th><th>Kondisi</th><th>Lokasi</th><th>Aksi</th></tr></thead><tbody>
      <?php foreach ($items as $item): ?><tr>
        <td><div class="device-cell"><div class="device-icon">▣</div><div><strong><?= e($item['nama']) ?></strong><small><?= e($item['kode']) ?></small></div></div></td>
        <td><?= e($item['kategori']) ?></td><td><?= e($item['merek']) ?></td><td><?= (int)$item['jumlah'] ?></td>
        <td><span class="badge <?= $item['kondisi'] === 'Baik' ? 'good' : ($item['kondisi'] === 'Rusak' ? 'bad' : 'warning') ?>"><?= e($item['kondisi']) ?></span></td>
        <td><?= e($item['lokasi']) ?></td>
        <td><div class="action-group"><a class="action edit" href="edit.php?id=<?= (int)$item['id'] ?>" title="Edit">✎</a><form method="post" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus perangkat ini?');"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><button class="action delete" type="submit" title="Hapus">⌫</button></form></div></td>
      </tr><?php endforeach; ?>
      </tbody></table></div>
      <?php endif; ?>
    </section>
    <footer class="footer">InventarisKu · Project CRUD RPL</footer>
  </main>
</div>
</body>
</html>
