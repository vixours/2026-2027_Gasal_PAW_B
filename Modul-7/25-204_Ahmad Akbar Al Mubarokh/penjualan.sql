CREATE DATABASE penjualan;
USE penjualan;

CREATE TABLE users (
    id_user TINYINT AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150) NOT NULL,
    hp VARCHAR(20) NOT NULL,
    level TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id_user, username)
);

CREATE TABLE pelanggan (
    id VARCHAR(20) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT
);

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL
);

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    supplier_id INT NOT NULL,
    CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id),
    UNIQUE KEY uk_kode_barang (kode_barang)
);

CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT NOT NULL,
    total INT NOT NULL,
    pelanggan_id VARCHAR(20) NOT NULL,
    CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
);

CREATE TABLE transaksi_detail (
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    harga INT NOT NULL,
    qty INT NOT NULL,
    CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY (transaksi_id,barang_id)
);


CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    waktu_bayar DATETIME NOT NULL,
    total INT NOT NULL,
    metode ENUM('Tunai','Transfer','EDC') NOT NULL,
    transaksi_id INT NOT NULL,
    CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
);
USE penjualan;

INSERT INTO supplier (nama, telp, alamat) VALUES
  ('PT Sumber Makmur', '081234567801', 'Jl. Merdeka No. 10, Surabaya'),
  ('CV Maju Jaya', '081234567802', 'Jl. Diponegoro No. 25, Malang'),
  ('UD Berkah Abadi', '081234567803', 'Jl. Ahmad Yani No. 7, Sidoarjo'),
  ('PT Cahaya Nusantara', '081234567804', 'Jl. Pahlawan No. 18, Gresik'),
  ('CV Sinar Terang', '081234567805', 'Jl. Veteran No. 33, Bangkalan');

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
  ('P001', 'Budi Santoso', 'L', '081311110001', 'Jl. Mawar No. 1, Surabaya'),
  ('P002', 'Siti Aminah', 'P', '081311110002', 'Jl. Melati No. 2, Malang'),
  ('P003', 'Agus Prasetyo', 'L', '081311110003', 'Jl. Kenanga No. 3, Sidoarjo'),
  ('P004', 'Dewi Lestari', 'P', '081311110004', 'Jl. Anggrek No. 4, Gresik'),
  ('P005', 'Rizky Ramadhan', 'L', '081311110005', 'Jl. Dahlia No. 5, Bangkalan');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
  ('BRG001', 'Beras 5 Kg', 65000, 100, 1),
  ('BRG002', 'Minyak Goreng 2 L', 38000, 80, 1),
  ('BRG003', 'Gula Pasir 1 Kg', 16000, 120, 2),
  ('BRG004', 'Tepung Terigu 1 Kg', 12000, 90, 2),
  ('BRG005', 'Kopi Bubuk 200 g', 22000, 60, 3),
  ('BRG006', 'Teh Celup Isi 25', 9500, 150, 3),
  ('BRG007', 'Mie Instan Dus', 110000, 40, 4),
  ('BRG008', 'Sabun Mandi', 4500, 200, 4),
  ('BRG009', 'Detergen 800 g', 18500, 70, 5),
  ('BRG010', 'Air Mineral Dus', 42000, 50, 5);


INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
  ('2026-10-01', 'Belanja bulanan', 216000, 'P001'),
  ('2026-10-03', 'Pembelian warung', 104000, 'P002'),
  ('2026-10-05', 'Stok toko kelontong', 412000, 'P003'),
  ('2026-10-08', 'Pembelian harian', 70000, 'P004'),
  ('2026-10-10', 'Pesanan acara keluarga', 461000, 'P005'),
  ('2026-10-12', 'Belanja mingguan', 78000, 'P001'),
  ('2026-10-15', 'Pembelian katering', 469000, 'P002'),
  ('2026-10-18', 'Restock warung kopi', 193000, 'P003'),
  ('2026-10-22', 'Belanja kebutuhan kos', 194000, 'P004'),
  ('2026-10-25', 'Pembelian grosir', 484000, 'P005');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
  (1, 1, 65000, 2),
  (1, 2, 38000, 1),
  (1, 3, 16000, 3),
  (2, 4, 12000, 5),
  (2, 5, 22000, 2),
  (3, 7, 110000, 3),
  (3, 8, 4500, 10),
  (3, 9, 18500, 2),
  (4, 6, 9500, 4),
  (4, 3, 16000, 2),
  (5, 1, 65000, 3),
  (5, 10, 42000, 4),
  (5, 2, 38000, 2),
  (5, 5, 22000, 1),
  (6, 8, 4500, 5),
  (6, 9, 18500, 3),
  (7, 1, 65000, 5),
  (7, 3, 16000, 6),
  (7, 4, 12000, 4),
  (8, 5, 22000, 4),
  (8, 6, 9500, 6),
  (8, 3, 16000, 3),
  (9, 7, 110000, 1),
  (9, 10, 42000, 2),
  (10, 2, 38000, 5),
  (10, 7, 110000, 2),
  (10, 9, 18500, 4);