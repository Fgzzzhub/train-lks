-- Schema tambahan untuk fitur Koordinator

  CREATE TABLE IF NOT EXISTS `berita` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `lomba_id` int(11) NOT NULL,
    `judul` varchar(255) NOT NULL,
    `isi` text DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `berita_lomba_id_idx` (`lomba_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

  CREATE TABLE IF NOT EXISTS `laporan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `lomba_id` int(11) NOT NULL,
    `judul` varchar(255) NOT NULL,
    `keterangan` text DEFAULT NULL,
    `file_path` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `laporan_lomba_id_idx` (`lomba_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
