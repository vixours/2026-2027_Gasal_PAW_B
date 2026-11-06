<?php
include "koneksi.php";

$id = (int) $_GET["id"];

// cek apakah barang sudah dipakai di transaksi_detail
$cek = mysqli_query($conn, "SELECT barang_id FROM transaksi_detail WHERE barang_id=$id");

if (mysqli_num_rows($cek) > 0) {
  // sudah dipakai -> tidak boleh dihapus
  echo "<script>
    alert('Barang tidak dapat dihapus karena masih digunakan pada transaksi.');
    window.location = 'barang.php';
  </script>";
} else {
  // belum dipakai -> boleh dihapus
  $query = "DELETE FROM barang WHERE id=$id";

  if (mysqli_query($conn, $query)) {
    header("Location: barang.php");
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
