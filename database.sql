CREATE DATABASE IF NOT EXISTS inventaris_komputer
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_komputer;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS perangkat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  kategori VARCHAR(60) NOT NULL,
  merek VARCHAR(60) NOT NULL,
  jumlah INT NOT NULL DEFAULT 1,
  kondisi ENUM('Baik','Perlu Perbaikan','Rusak') NOT NULL DEFAULT 'Baik',
  lokasi VARCHAR(100) NOT NULL,
  tanggal_masuk DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Akun demo: admin / password
-- Password disimpan sebagai hash bcrypt.
INSERT INTO admins (username, password)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.')
ON DUPLICATE KEY UPDATE username = VALUES(username);

INSERT INTO perangkat (kode, nama, kategori, merek, jumlah, kondisi, lokasi, tanggal_masuk) VALUES
('PC-001', 'Monitor 24 inci', 'Monitor', 'LG', 5, 'Baik', 'Lab Komputer', '2026-01-10'),
('PC-002', 'Keyboard USB', 'Aksesori', 'Logitech', 10, 'Baik', 'Lab Komputer', '2026-01-12'),
('PC-003', 'Laptop', 'Komputer', 'ASUS', 3, 'Perlu Perbaikan', 'Ruang Guru', '2026-02-05')
ON DUPLICATE KEY UPDATE kode = VALUES(kode);
