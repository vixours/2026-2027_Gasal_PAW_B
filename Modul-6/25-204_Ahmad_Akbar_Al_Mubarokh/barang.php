<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Master Barang</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
  <div class="page-header">
    <a href="index.php" class="btn btn-default pull-right">Kembali</a>
    <h2 style="color:#337ab7;">Data Master Barang</h2>
  </div>

  <table class="table table-bordered">
    <tr class="info">
      <th>No</th>
      <th>Kode</th>
      <th>Nama Barang</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Tindakan</th>
    </tr>
    <?php
    $query = "SELECT id, kode_barang, nama_barang, harga, stok FROM barang";
    $result = mysqli_query($conn, $query);
    $no = 1;

    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $row["kode_barang"]; ?></td>
      <td><?php echo $row["nama_barang"]; ?></td>
      <td>Rp <?php echo number_format($row["harga"], 0, ",", "."); ?></td>
      <td><?php echo $row["stok"]; ?></td>
      <td>
        <a href="barang_hapus.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin akan menghapus barang ini?')">Hapus</a>
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
