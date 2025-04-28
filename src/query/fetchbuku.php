<?php
include dirname(__FILE__) . '/../koneksi.php';

$query = "SELECT 
            id_peserta,
            nama_peserta,
            email_peserta,
            telp_peserta,
            alamat_peserta,
            instansi,
            id_mentor,
            mentor,
            tanggal_mulai,
            tanggal_selesai
          FROM peserta 
          ORDER BY id_peserta ASC";

$result = mysqli_query($conn, $query);
$hasil = array();

while ($row = mysqli_fetch_array($result)) {
  $hasil[] = $row;
}
