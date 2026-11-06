<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Transaksi</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
  <div class="page-header">
    <a href="index.php" class="btn btn-default pull-right">Kembali</a>
    <h2 style="color:#337ab7;">Data Transaksi</h2>
  </div>

  <table class="table table-bordered">
    <tr class="info">
      <th>No</th>
      <th>Tanggal</th>
      <th>Pelanggan</th>
      <th>Keterangan</th>
      <th>Total</th>
      <th>Tindakan</th>
    </tr>
    <?php
    $query = "SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama
              FROM transaksi t
              JOIN pelanggan p ON t.pelanggan_id = p.id
              ORDER BY t.id";
    $result = mysqli_query($conn, $query);
    $no = 1;

    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $row["waktu_transaksi"]; ?></td>
      <td><?php echo $row["nama"]; ?></td>
      <td><?php echo $row["keterangan"]; ?></td>
      <td>Rp <?php echo number_format($row["total"], 0, ",", "."); ?></td>
      <td>
        <a href="detail.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary btn-sm">Detail</a>
      </td>
    </tr>
    <?php
      $no = $no + 1;
    }
    ?>
  </table>
</div>
</body>
</html>
