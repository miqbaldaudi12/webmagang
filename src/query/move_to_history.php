<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

$id_peserta = $_GET['id'];

// First get the peserta data
$query = "SELECT * FROM peserta WHERE id_peserta = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_peserta);
$stmt->execute();
$result = $stmt->get_result();
$peserta = $result->fetch_assoc();

if ($peserta) {
    // Calculate remaining days to determine status
    $today = new DateTime();
    $end_date = new DateTime($peserta['tanggal_selesai']);
    $interval = $today->diff($end_date);
    $remaining_days = $interval->format('%R%a');

    // Determine status
    $status = ($remaining_days <= 0) ? 'Selesai' : 'Tidak Selesai';

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into history
        $insert_query = "INSERT INTO history (
            id_peserta, nama_peserta, email_peserta, telp_peserta, 
            alamat_peserta, instansi, id_mentor, mentor, 
            tanggal_mulai, tanggal_selesai, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param(
            "isssssissss",
            $peserta['id_peserta'],
            $peserta['nama_peserta'],
            $peserta['email_peserta'],
            $peserta['telp_peserta'],
            $peserta['alamat_peserta'],
            $peserta['instansi'],
            $peserta['id_mentor'],
            $peserta['mentor'],
            $peserta['tanggal_mulai'],
            $peserta['tanggal_selesai'],
            $status
        );
        $stmt->execute();

        // Delete from peserta
        $delete_query = "DELETE FROM peserta WHERE id_peserta = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id_peserta);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Data peserta berhasil dipindahkan ke history!'
        ];
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Gagal memindahkan data: ' . $e->getMessage()
        ];
    }
} else {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Data peserta tidak ditemukan!'
    ];
}

header("Location: ../admin.php");
exit();
