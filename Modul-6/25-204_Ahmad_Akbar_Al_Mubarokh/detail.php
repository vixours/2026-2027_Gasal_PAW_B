<?php
include "koneksi.php";

$id = (int) $_GET["id"];
$pesan = "";

// ===== SIMPAN DETAIL BARU =====
if (isset($_POST["simpan"])) {
  $barang_id = (int) $_POST["barang_id"];
  $qty = (int) $_POST["qty"];

  // cek di sisi server: barang tidak boleh sudah ada di transaksi ini
  $cek = mysqli_query($conn, "SELECT barang_id FROM transaksi_detail WHERE transaksi_id=$id AND barang_id=$barang_id");

  if (mysqli_num_rows($cek) > 0) {
    $pesan = "Barang sudah ada di transaksi ini.";
  } else if ($qty < 1) {
    $pesan = "Qty minimal 1.";
  } else {
    // harga diambil dari tabel barang
    $rb = mysqli_query($conn, "SELECT harga FROM barang WHERE id=$barang_id");
    $b = mysqli_fetch_assoc($rb);
    $harga = $b["harga"];

    $query = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
              VALUES ($id, $barang_id, $harga, $qty)";

    if (mysqli_query($conn, $query)) {
      // hitung ulang total transaksi dari seluruh detailnya
      $update = "UPDATE transaksi SET total = (
                   SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id=$id
                 ) WHERE id=$id";
      mysqli_query($conn, $update);

      header("Location: detail.php?id=$id");
      exit;
    } else {
      $pesan = "Error: " . mysqli_error($conn);
    }
  }
}

// ===== DATA MASTER =====
$qm = "SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama
       FROM transaksi t
       JOIN pelanggan p ON t.pelanggan_id = p.id
       WHERE t.id=$id";
$master = mysqli_fetch_assoc(mysqli_query($conn, $qm));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Detail Transaksi</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
  <div class="page-header">
    <a href="transaksi.php" class="btn btn-default pull-right">Kembali</a>
    <h2 style="color:#337ab7;">Detail Transaksi #<?php echo $master["id"]; ?></h2>
  </div>

  <?php if ($pesan != "") { ?>
    <div class="alert alert-danger"><?php echo $pesan; ?></div>
  <?php } ?>

  <!-- MASTER -->
  <table class="table table-bordered" style="width:60%;">
    <tr><th class="info" width="30%">Tanggal</th><td><?php echo $master["waktu_transaksi"]; ?></td></tr>
    <tr><th class="info">Pelanggan</th><td><?php echo $master["nama"]; ?></td></tr>
    <tr><th class="info">Keterangan</th><td><?php echo $master["keterangan"]; ?></td></tr>
    <tr><th class="info">Total</th><td><b>Rp <?php echo number_format($master["total"], 0, ",", "."); ?></b></td></tr>
  </table>

  <!-- DETAIL -->
  <h4>Detail Barang</h4>
  <table class="table table-bordered">
    <tr class="info">
      <th>No</th>
      <th>Barang</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>Subtotal</th>
    </tr>
    <?php
    $qd = "SELECT b.nama_barang, d.harga, d.qty
           FROM transaksi_detail d
           JOIN barang b ON d.barang_id = b.id
           WHERE d.transaksi_id=$id";
    $rd = mysqli_query($conn, $qd);
    $no = 1;

    while ($row = mysqli_fetch_assoc($rd)) {
      $subtotal = $row["harga"] * $row["qty"];
    ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $row["nama_barang"]; ?></td>
      <td>Rp <?php echo number_format($row["harga"], 0, ",", "."); ?></td>
      <td><?php echo $row["qty"]; ?></td>
      <td>Rp <?php echo number_format($subtotal, 0, ",", "."); ?></td>
    </tr>
    <?php
      $no = $no + 1;
    }
    ?>
  </table>

  <!-- FORM INPUT DETAIL -->
  <h4>Tambah Barang</h4>
  <form method="post" class="form-inline">
    <select name="barang_id" class="form-control" required>
      <option value="">-- Pilih Barang --</option>
      <?php
      // hanya barang yang BELUM ada di transaksi ini
      $qb = "SELECT id, nama_barang, harga FROM barang
             WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id=$id)";
      $rb = mysqli_query($conn, $qb);

      while ($b = mysqli_fetch_assoc($rb)) {
      ?>
      <option value="<?php echo $b["id"]; ?>">
        <?php echo $b["nama_barang"]; ?> - Rp <?php echo number_format($b["harga"], 0, ",", "."); ?>
      </option>
      <?php } ?>
    </select>
    <input type="number" name="qty" class="form-control" placeholder="Qty" min="1" required>
    <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
  </form>
</div>
</body>
</html>
