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
$sheet->setCellValue('A1', 'ID Mentor');
$sheet->setCellValue('B1', 'Nama Mentor');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Alamat');

// Ambil data dari database
$query = "SELECT id_mentor, nama_mentor, email_mentor, alamat_mentor FROM mentor";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $rowIndex = 2; // Mulai dari baris kedua setelah header
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['id_mentor']);
        $sheet->setCellValue('B' . $rowIndex, $row['nama_mentor']);
        $sheet->setCellValue('C' . $rowIndex, $row['email_mentor']);
        $sheet->setCellValue('D' . $rowIndex, $row['alamat_mentor']);
        $rowIndex++;
    }
}

// Atur nama file dan header agar dapat diunduh sebagai Excel
$filename = "data_mentor.xlsx";
$filePath = __DIR__ . "/$filename"; // Simpan di direktori yang sama

// Simpan file Excel ke server sebelum mengirimkannya
$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/tes_mentor.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
