<?php
require __DIR__ . '/config.php';
wajib_login();

$total = (int)$pdo->query('SELECT COUNT(*) FROM perangkat')->fetchColumn();
$baik = (int)$pdo->query("SELECT COUNT(*) FROM perangkat WHERE kondisi = 'Baik'")->fetchColumn();
$perbaikan = (int)$pdo->query("SELECT COUNT(*) FROM perangkat WHERE kondisi = 'Perlu Perbaikan'")->fetchColumn();
$rusak = (int)$pdo->query("SELECT COUNT(*) FROM perangkat WHERE kondisi = 'Rusak'")->fetchColumn();
$terbaru = $pdo->query('SELECT * FROM perangkat ORDER BY id DESC LIMIT 5')->fetchAll();
$flash = ambil_flash();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | InventarisKu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
  <aside class="sidebar">
    <a class="brand" href="index.php"><span class="brand-mark small">IK</span><span>Inventaris<span class="brand-light">Ku</span></span></a>
    <p class="nav-label">MENU UTAMA</p>
    <a class="nav-link active" href="index.php"><span>▦</span> Dashboard</a>
    <a class="nav-link" href="perangkat.php"><span>▤</span> Data Perangkat</a>
    <div class="sidebar-bottom">
      <div class="profile-chip"><div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1))) ?></div><div><strong><?= e($_SESSION['admin_username'] ?? 'Admin') ?></strong><small>Administrator</small></div></div>
      <a class="nav-link logout-link" href="logout.php"><span>↪</span> Keluar</a>
    </div>
  </aside>

  <main class="main-content">
    <header class="topbar"><div><p class="eyebrow">OVERVIEW</p><h1>Dashboard</h1></div><div class="topbar-right"><span class="status-dot"></span> Sistem aktif <div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1))) ?></div></div></header>
    <?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <section class="welcome-panel">
      <div><p class="eyebrow light">RINGKASAN INVENTARIS</p><h2>Halo, <?= e($_SESSION['admin_username'] ?? 'Admin') ?> 👋</h2><p>Pantau dan kelola perangkat komputer dalam satu tempat.</p><a class="btn white" href="perangkat.php">Kelola perangkat <span>→</span></a></div>
      <div class="welcome-art" aria-hidden="true"><div class="monitor"><div></div></div><div class="monitor-stand"></div><div class="keyboard"></div></div>
    </section>

    <section class="stats-grid">
      <article class="stat-card"><div class="stat-icon blue">▣</div><p>Total jenis perangkat</p><div class="stat-number"><?= $total ?></div><small>Data perangkat terdaftar</small></article>
      <article class="stat-card"><div class="stat-icon green">✓</div><p>Kondisi baik</p><div class="stat-number"><?= $baik ?></div><small>Siap digunakan</small></article>
      <article class="stat-card"><div class="stat-icon orange">⚒</div><p>Perlu perbaikan</p><div class="stat-number"><?= $perbaikan ?></div><small>Perlu pemeriksaan</small></article>
      <article class="stat-card"><div class="stat-icon red">!</div><p>Rusak</p><div class="stat-number"><?= $rusak ?></div><small>Belum dapat digunakan</small></article>
    </section>

    <section class="panel">
      <div class="panel-heading"><div><h2>Perangkat terbaru</h2><p>Data terakhir yang ditambahkan ke sistem.</p></div><a class="text-link" href="perangkat.php">Lihat semua →</a></div>
      <?php if (!$terbaru): ?><div class="empty-state">Belum ada data perangkat.</div><?php else: ?>
      <div class="table-wrap"><table><thead><tr><th>Perangkat</th><th>Kategori</th><th>Merek</th><th>Jumlah</th><th>Kondisi</th></tr></thead><tbody>
      <?php foreach ($terbaru as $item): ?><tr><td><div class="device-cell"><div class="device-icon">▣</div><div><strong><?= e($item['nama']) ?></strong><small><?= e($item['kode']) ?></small></div></div></td><td><?= e($item['kategori']) ?></td><td><?= e($item['merek']) ?></td><td><?= (int)$item['jumlah'] ?></td><td><span class="badge <?= $item['kondisi'] === 'Baik' ? 'good' : ($item['kondisi'] === 'Rusak' ? 'bad' : 'warning') ?>"><?= e($item['kondisi']) ?></span></td></tr><?php endforeach; ?>
      </tbody></table></div>
      <?php endif; ?>
    </section>
    <footer class="footer">InventarisKu · Project CRUD RPL</footer>
  </main>
</div>
</body>
</html>
