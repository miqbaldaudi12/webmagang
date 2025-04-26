<?php
// Sertakan koneksi database
include '../koneksi.php';

// Load library PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul kolom dalam file Excel
$sheet->setCellValue('A1', 'ID Peserta');
$sheet->setCellValue('B1', 'Nama Peserta');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Alamat Peserta');
$sheet->setCellValue('E1', 'Instansi');
$sheet->setCellValue('F1', 'ID Mentor');
$sheet->setCellValue('G1', 'Nama Mentor');

// Ambil data dari database dengan join ke tabel mentor
$query = "SELECT 
            peserta.id_peserta, 
            peserta.nama_peserta, 
            peserta.email_peserta, 
            peserta.alamat_peserta, 
            peserta.instansi, 
            peserta.id_mentor, 
            mentor.nama_mentor 
          FROM peserta
          LEFT JOIN mentor ON peserta.id_mentor = mentor.id_mentor";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $rowIndex = 2; // Mulai dari baris kedua setelah header
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['id_peserta']);
        $sheet->setCellValue('B' . $rowIndex, $row['nama_peserta']);
        $sheet->setCellValue('C' . $rowIndex, $row['email_peserta']);
        $sheet->setCellValue('D' . $rowIndex, $row['alamat_peserta']);
        $sheet->setCellValue('E' . $rowIndex, $row['instansi']);
        $sheet->setCellValue('F' . $rowIndex, $row['id_mentor']);
        $sheet->setCellValue('G' . $rowIndex, $row['nama_mentor']);
        $rowIndex++;
    }
}

// Atur nama file dan header agar dapat diunduh sebagai Excel
$filename = "data_peserta.xlsx";
$filePath = __DIR__ . "/$filename"; // Simpan di direktori yang sama

// Simpan file Excel ke server sebelum mengirimkannya
$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/tes_peserta.xlsx');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
