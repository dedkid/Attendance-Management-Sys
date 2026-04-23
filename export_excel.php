<?php
include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$timein_sql = "SELECT t.name, t.datetime, t.location 
           FROM time_in t
           INNER JOIN users u ON t.name = u.name
           WHERE u.position = 'employee'
           ORDER BY t.datetime DESC";
$timein_result = mysqli_query($conn, $timein_sql);

$timeout_sql = "SELECT t.name, t.datetime, t.overtime, t.hours 
            FROM time_out t
            INNER JOIN users u ON t.name = u.name
            WHERE u.position = 'employee'
            ORDER BY t.datetime DESC";
$timeout_result = mysqli_query($conn, $timeout_sql);

$sheet->setCellValue('A1', 'Employee Time In Record');
$sheet->setCellValue('A2', 'Name');
$sheet->setCellValue('B2', 'Date and Time');
$sheet->setCellValue('C2', 'Location');

$sheet->setCellValue('F1', 'Employee Time Out Record');
$sheet->setCellValue('F2', 'Name');
$sheet->setCellValue('G2', 'Date and Time');
$sheet->setCellValue('H2', 'Overtime');
$sheet->setCellValue('I2', 'Hours');

$row = 3;
while ($rowIn = mysqli_fetch_assoc($timein_result)) {
    $sheet->setCellValue('A' . $row, $rowIn['name']);
    $sheet->setCellValue('B' . $row, $rowIn['datetime']);
    $sheet->setCellValue('C' . $row, $rowIn['location']);
    $row++;
}

$row = 3;
while ($rowOut = mysqli_fetch_assoc($timeout_result)) {
    $sheet->setCellValue('F' . $row, $rowOut['name']);
    $sheet->setCellValue('G' . $row, $rowOut['datetime']);
    $sheet->setCellValue('H' . $row, $rowOut['overtime']);
    $sheet->setCellValue('I' . $row, $rowOut['hours']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Employee Attendance Record.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>