<?php
require '../vendor/autoload.php';
include '../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers to match admin.php table structure
$headers = [
    'ID Peserta',
    'Nama Peserta',
    'Email',
    'No. Telp',
    'Alamat',
    'Instansi',
    'Mentor',
    'Tanggal Mulai',
    'Tanggal Selesai',
    'Status'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Query data
$query = "SELECT * FROM peserta ORDER BY id_peserta ASC";
$result = mysqli_query($conn, $query);
$row_number = 2;

while ($row = mysqli_fetch_array($result)) {
    // Calculate remaining days and status
    $today = new DateTime();
    $end_date = new DateTime($row['tanggal_selesai']);
    $interval = $today->diff($end_date);
    $remaining_days = $interval->format('%R%a');

    // Determine status text
    if ($remaining_days <= 0) {
        $status = 'Selesai';
    } else if ($remaining_days <= 7) {
        $status = $remaining_days . ' hari (Segera Selesai)';
    } else if ($remaining_days <= 14) {
        $status = $remaining_days . ' hari';
    } else {
        $status = $remaining_days . ' hari';
    }

    $sheet->setCellValue('A' . $row_number, $row['id_peserta']);
    $sheet->setCellValue('B' . $row_number, $row['nama_peserta']);
    $sheet->setCellValue('C' . $row_number, $row['email_peserta']);
    $sheet->setCellValue('D' . $row_number, $row['telp_peserta']);
    $sheet->setCellValue('E' . $row_number, $row['alamat_peserta']);
    $sheet->setCellValue('F' . $row_number, $row['instansi']);
    $sheet->setCellValue('G' . $row_number, $row['mentor']);
    $sheet->setCellValue('H' . $row_number, date('d/m/Y', strtotime($row['tanggal_mulai'])));
    $sheet->setCellValue('I' . $row_number, date('d/m/Y', strtotime($row['tanggal_selesai'])));
    $sheet->setCellValue('J' . $row_number, $status);

    $row_number++;
}

// Auto-size columns
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set header style
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E2EFDA']
    ]
];
$sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

// Create Excel file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data_Peserta_Magang.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
