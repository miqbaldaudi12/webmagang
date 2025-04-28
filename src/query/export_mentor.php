<?php
require '../vendor/autoload.php';
include '../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers to match admin.php mentor table
$headers = [
    'ID Mentor',
    'Nama Mentor',
    'Email',
    'No. Telp',
    'Alamat'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Query data
$query = "SELECT * FROM mentor ORDER BY id_mentor ASC";
$result = mysqli_query($conn, $query);
$row_number = 2;

while ($row = mysqli_fetch_array($result)) {
    $sheet->setCellValue('A' . $row_number, $row['id_mentor']);
    $sheet->setCellValue('B' . $row_number, $row['nama_mentor']);
    $sheet->setCellValue('C' . $row_number, $row['email_mentor']);
    $sheet->setCellValue('D' . $row_number, $row['telp_mentor']);
    $sheet->setCellValue('E' . $row_number, $row['alamat_mentor']);

    $row_number++;
}

// Auto-size columns
foreach (range('A', 'E') as $col) {
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
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Create Excel file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data_Mentor.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
