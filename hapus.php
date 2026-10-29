<?php
require __DIR__ . '/config.php';
wajib_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perangkat.php');
    exit;
}
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    flash('ID perangkat tidak valid.', 'error');
    header('Location: perangkat.php');
    exit;
}
$stmt = $pdo->prepare('DELETE FROM perangkat WHERE id = ?');
$stmt->execute([$id]);
flash($stmt->rowCount() ? 'Data perangkat berhasil dihapus.' : 'Data perangkat tidak ditemukan.', $stmt->rowCount() ? 'success' : 'error');
header('Location: perangkat.php');
exit;
