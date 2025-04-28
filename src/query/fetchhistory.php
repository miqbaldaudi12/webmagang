<?php
include dirname(__FILE__) . '/../koneksi.php';

// Get main history data
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
            tanggal_selesai,
            status
          FROM history 
          ORDER BY tanggal_mulai ASC";

$result = mysqli_query($conn, $query);
$hasil_history = array();

while ($row = mysqli_fetch_array($result)) {
    $hasil_history[] = $row;
}

// Get summary statistics
$query_total = "SELECT COUNT(*) as total FROM history";
$total_history = mysqli_fetch_assoc(mysqli_query($conn, $query_total))['total'];

$query_completed = "SELECT COUNT(*) as completed FROM history WHERE status = 'Selesai'";
$completed_count = mysqli_fetch_assoc(mysqli_query($conn, $query_completed))['completed'];

$query_incomplete = "SELECT COUNT(*) as incomplete FROM history WHERE status = 'Tidak Selesai'";
$incomplete_count = mysqli_fetch_assoc(mysqli_query($conn, $query_incomplete))['incomplete'];

// Calculate percentages
$completed_percent = $total_history > 0 ? round(($completed_count / $total_history) * 100) : 0;
$incomplete_percent = $total_history > 0 ? round(($incomplete_count / $total_history) * 100) : 0;
