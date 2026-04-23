<?php
include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();

// Employees sheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Employees');
$dates = array(); // Store unique dates

$timein_sql = "SELECT u.name, eu.department, eu.work_hrs, u.position, t.datetime AS timein, o.datetime AS timeout, n.type, n.date
                FROM users u
                LEFT JOIN emp_info eu ON u.name = eu.name
                LEFT JOIN time_in t ON u.name = t.name
                LEFT JOIN time_out o ON u.name = o.name
                LEFT JOIN notices n ON u.name = n.name
                WHERE u.position = 'employee'
                ORDER BY CASE eu.department
                    WHEN 'IT' THEN 1
                    WHEN 'Marketing' THEN 2
                    WHEN 'HR' THEN 3
                    WHEN 'Accounting' THEN 4
                    WHEN 'Admin' THEN 5
                    ELSE 6
                END, t.datetime ASC";


$etimein_sql = "SELECT t.name, t.datetime, t.location, u.position 
           FROM time_in t
           INNER JOIN users u ON t.name = u.name
           WHERE u.position = 'employee'
           ORDER BY t.datetime DESC";
$etimein_result = mysqli_query($conn, $etimein_sql);

$itimein_sql = "SELECT t.name, t.datetime, t.location, u.position 
           FROM time_in t
           INNER JOIN users u ON t.name = u.name
           WHERE u.position = 'intern'
           ORDER BY t.datetime DESC";
$itimein_result = mysqli_query($conn, $itimein_sql);

$etimeout_sql = "SELECT t.name, t.datetime, t.overtime, t.hours, u.position 
            FROM time_out t
            INNER JOIN users u ON t.name = u.name
            WHERE u.position = 'employee'
            ORDER BY t.datetime DESC";
$etimeout_result = mysqli_query($conn, $etimeout_sql);

$itimeout_sql = "SELECT t.name, t.datetime, t.overtime, t.hours, u.position 
            FROM time_out t
            INNER JOIN users u ON t.name = u.name
            WHERE u.position = 'intern'
            ORDER BY t.datetime DESC";
$itimeout_result = mysqli_query($conn, $itimeout_sql);


$sheet->setCellValue('A2', 'Name');

$sheet->setCellValue('B2', 'Date and Time');
$sheet->setCellValue('C2', 'Location');
$sheet->setCellValue('D2', 'Position');

$sheet->setCellValue('G1', 'Employee Time Out Record');
$sheet->setCellValue('G2', 'Name');
$sheet->setCellValue('H2', 'Date and Time');
$sheet->setCellValue('I2', 'Overtime');
$sheet->setCellValue('J2', 'Hours');
$sheet->setCellValue('K2', 'Position');

$sheet->setCellValue('N1', 'Intern Time In Record');
$sheet->setCellValue('N2', 'Name');
$sheet->setCellValue('O2', 'Date and Time');
$sheet->setCellValue('P2', 'Location');
$sheet->setCellValue('Q2', 'Position');

$sheet->setCellValue('T1', 'Intern Time Out Record');
$sheet->setCellValue('T2', 'Name');
$sheet->setCellValue('U2', 'Date and Time');
$sheet->setCellValue('V2', 'Overtime');
$sheet->setCellValue('W2', 'Hours');
$sheet->setCellValue('X2', 'Position');

$row = 3;
while ($rowIn = mysqli_fetch_assoc($etimein_result)) {
    $sheet->setCellValue('A' . $row, $rowIn['name']);
    $sheet->setCellValue('B' . $row, $rowIn['datetime']);
    $sheet->setCellValue('C' . $row, $rowIn['location']);
    $sheet->setCellValue('D' . $row, $rowIn['position']);
    $row++;
}

$row = 3;
while ($rowIn = mysqli_fetch_assoc($itimein_result)) {
    $sheet->setCellValue('N' . $row, $rowIn['name']);
    $sheet->setCellValue('O' . $row, $rowIn['datetime']);
    $sheet->setCellValue('P' . $row, $rowIn['location']);
    $sheet->setCellValue('Q' . $row, $rowIn['position']);
    $row++;
}

$row = 3;
while ($rowOut = mysqli_fetch_assoc($etimeout_result)) {
    $sheet->setCellValue('G' . $row, $rowOut['name']);
    $sheet->setCellValue('H' . $row, $rowOut['datetime']);
    $sheet->setCellValue('I' . $row, $rowOut['overtime']);
    $sheet->setCellValue('J' . $row, $rowOut['hours']);
    $sheet->setCellValue('K' . $row, $rowOut['position']);

    $row++;
}

sort($dates);
// Set headers for merged date columns
$columnIndex = 5;
foreach ($dates as $date) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 1, $date);
    $sheet->mergeCellsByColumnAndRow($columnIndex, 1, $columnIndex + 1, 1);
    $sheet->setCellValueByColumnAndRow($columnIndex, 2, 'Ti');
    $sheet->getStyleByColumnAndRow($columnIndex, 2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->setCellValueByColumnAndRow($columnIndex + 1, 2, 'To');
    $sheet->getStyleByColumnAndRow($columnIndex + 1, 2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    

    // Rotate text up for the date cells
    $sheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setTextRotation(90);
    $sheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setTextRotation(90);

    // Center the content in the date cells
    $sheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Set border style for the date cells
    $sheet->getStyleByColumnAndRow($columnIndex, 1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $columnIndex += 2;
}

// Apply borders to the name, department, position, and schedule columns
$sheet->getStyle('A3:D' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// If there is time in data in the respective date columns, fill with green color
$row = 3;


while ($rowOut = mysqli_fetch_assoc($itimeout_result)) {
    $sheet->setCellValue('T' . $row, $rowOut['name']);
    $sheet->setCellValue('U' . $row, $rowOut['datetime']);
    $sheet->setCellValue('V' . $row, $rowOut['overtime']);
    $sheet->setCellValue('W' . $row, $rowOut['hours']);
    $sheet->setCellValue('x' . $row, $rowOut['position']);

    $row++;
}


// Set the height of row A
$sheet->getRowDimension(1)->setRowHeight(65);
// Auto-size the columns
foreach (range('A', $sheet->getHighestColumn()) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}



// Interns sheet
$internSheet = $spreadsheet->createSheet();
$internSheet->setTitle('Interns');

$timein_sql = "SELECT u.name, ii.department, ii.work_hrs, u.position, t.datetime AS timein, o.datetime AS timeout, n.type, n.date
                FROM users u
                LEFT JOIN int_info ii ON u.name = ii.name
                LEFT JOIN time_in t ON u.name = t.name
                LEFT JOIN time_out o ON u.name = o.name
                LEFT JOIN notices n ON u.name = n.name
                WHERE u.position = 'intern'
                ORDER BY CASE ii.department
                    WHEN 'IT' THEN 1
                    WHEN 'Marketing' THEN 2
                    WHEN 'HR' THEN 3
                    WHEN 'Accounting' THEN 4
                    WHEN 'Admin' THEN 5
                    ELSE 6
                END, t.datetime ASC";
$timein_result = mysqli_query($conn, $timein_sql);

$internSheet->setCellValue('A2', 'Name');
$internSheet->setCellValue('B2', 'Department');
$internSheet->setCellValue('C2', 'Position');
$internSheet->setCellValue('D2', 'Schedule');

// Apply borders to the headers
$internSheet->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$row = 3;

$nameTracker = array(); // Track names
$timeinData = array(); // Store time in data for each name
$noticeData = array(); // Store notice data for each name and date

while ($rowIn = mysqli_fetch_assoc($timein_result)) {
    $name = $rowIn['name'];
    $date = $rowIn['timein'] ? date('Y-m-d', strtotime($rowIn['timein'])) : '';
    $timein = $rowIn['timein'] ? date('H:i:s', strtotime($rowIn['timein'])) : '';
    $timeout = $rowIn['timeout'] ? date('H:i:s', strtotime($rowIn['timeout'])) : '';

    // Check if date exists in the dates array
    if (!in_array($date, $dates) && $date !== '') {
        $dates[] = $date;
    }

    // Store time in and time out data for each name and date
    $timeinData[$name][$date] = array(
        'timein' => $timein,
        'timeout' => $timeout
    );

    // Print the name only if it hasn't been printed before
    if (!in_array($name, $nameTracker)) {
        $internSheet->setCellValue('A' . $row, $name);
        $internSheet->setCellValue('B' . $row, $rowIn['department']);
        $internSheet->setCellValue('C' . $row, $rowIn['position']);
        $internSheet->setCellValue('D' . $row, $rowIn['work_hrs']);

        // Track the printed name
        $nameTracker[] = $name;
    } else {
        $row--; // Decrement the row if the name has been printed before to overwrite the whitespace
    }

    // Check if the name has an associated notice with date and type
    if (!empty($rowIn['date']) && !empty($rowIn['type'])) {
        $noticeDate = date('Y-m-d', strtotime($rowIn['date']));
        $noticeType = $rowIn['type'];

        // Store the notice data in the noticeData array
        if (!isset($noticeData[$name][$noticeDate])) {
            $noticeData[$name][$noticeDate] = array(
                'type' => $noticeType
            );
        }

        // Set the values in Ti and To based on the notice type
        if ($noticeType == 'School Initiated Leave') {
            $timeinData[$name][$noticeDate]['timein'] = 'SIL';
            $timeinData[$name][$noticeDate]['timeout'] = 'SIL';
        } elseif ($noticeType == 'Sick Leave') {
            $timeinData[$name][$noticeDate]['timein'] = 'SL';
            $timeinData[$name][$noticeDate]['timeout'] = 'SL';
        } elseif ($noticeType == 'Absence without Leave') {
            $timeinData[$name][$noticeDate]['timein'] = 'ABW';
            $timeinData[$name][$noticeDate]['timeout'] = 'ABW';
        } elseif ($noticeType == 'Late (No Time in)') {
            $timeinData[$name][$noticeDate]['timein'] = 'L';
            $timeinData[$name][$noticeDate]['timeout'] = 'L';
        } elseif ($noticeType == 'Unidentified') {
            $timeinData[$name][$noticeDate]['timein'] = '?';
            $timeinData[$name][$noticeDate]['timeout'] = '?';
        } elseif ($noticeType == 'Planned Leave') {
            $timeinData[$name][$noticeDate]['timein'] = 'PL';
            $timeinData[$name][$noticeDate]['timeout'] = 'PL';
        } 

        // Add the notice date to the dates array if it doesn't exist
        if (!in_array($noticeDate, $dates)) {
            $dates[] = $noticeDate;
        }
    }

    $row++;
}

sort($dates);
// Set headers for merged date columns
$columnIndex = 5;
foreach ($dates as $date) {
    $internSheet->setCellValueByColumnAndRow($columnIndex, 1, $date);
    $internSheet->mergeCellsByColumnAndRow($columnIndex, 1, $columnIndex + 1, 1);
    $internSheet->setCellValueByColumnAndRow($columnIndex, 2, 'Ti');
    $internSheet->getStyleByColumnAndRow($columnIndex, 2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $internSheet->setCellValueByColumnAndRow($columnIndex + 1, 2, 'To');
    $internSheet->getStyleByColumnAndRow($columnIndex + 1, 2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    

    // Rotate text up for the date cells
    $internSheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setTextRotation(90);
    $internSheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setTextRotation(90);

    // Center the content in the date cells
    $internSheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $internSheet->getStyleByColumnAndRow($columnIndex, 1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $internSheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $internSheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Set border style for the date cells
    $internSheet->getStyleByColumnAndRow($columnIndex, 1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $internSheet->getStyleByColumnAndRow($columnIndex + 1, 1)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $columnIndex += 2;
}

// Apply borders to the name, department, position, and schedule columns
$internSheet->getStyle('A3:D' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// If there is time in data in the respective date columns, fill with green color
$row = 3;
foreach ($nameTracker as $name) {
    $columnIndex = 5;
    foreach ($dates as $date) {
        $timein = isset($timeinData[$name][$date]['timein']) ? $timeinData[$name][$date]['timein'] : '';
        $timeout = isset($timeinData[$name][$date]['timeout']) ? $timeinData[$name][$date]['timeout'] : '';

        // Check if the Ti value is "PL" and replace the cell value with "PL" instead of filling it with green color
        if ($timein == 'PL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex, $row, 'PL');
        } elseif ($timein == 'SIL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex, $row, 'SIL');
        } elseif ($timein == 'SL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex, $row, 'SL');
        } elseif ($timein == 'ABW') {
            $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
        } elseif ($timein == 'L') {
            $internSheet->setCellValueByColumnAndRow($columnIndex, $row, 'L');
        } elseif ($timein == '?') {
            $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        } else {
            // Set the fill color of the cell in the Ti column to green if time in is not empty
            if (!empty($timein)) {
                // Get the time portion from the timein value
                $time = strtotime($timein);
                $timeFormatted = date('H:i', $time);
        
                // Check if the time is past 8:01
                if ($timeFormatted >= '08:01') {
                    $internSheet->setCellValueByColumnAndRow($columnIndex, $row, 'L');
                } else {
                    $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
                }
            }
        }

        // Check if the To value is "PL" and replace the cell value with "PL" instead of filling it with green color
        if ($timeout == 'PL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'PL');
        } elseif ($timeout == 'SIL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'SIL');
        } elseif ($timeout == 'SL') {
            $internSheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'SL');
        } elseif ($timeout == 'ABW') {
            $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
        } elseif ($timeout == 'L') {
            $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        } elseif ($timeout == '?') {
            $internSheet->setCellValueByColumnAndRow($columnIndex + 1, $row, '?');
        } else {
            // Set the fill color of the cell in the To column to green if time out is not empty
            if (!empty($timeout)) {
                $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
            }
        }

        // Center the content in the time in and time out cells
        $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Set border style for the time in and time out cells
        $internSheet->getStyleByColumnAndRow($columnIndex, $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $internSheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $columnIndex += 2;
    }
    $row++;
}


// Set the height of row A
$internSheet->getRowDimension(1)->setRowHeight(65);
// Auto-size the columns
foreach (range('A', $internSheet->getHighestColumn()) as $column) {
    $internSheet->getColumnDimension($column)->setAutoSize(true);
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Employee Attendance Record.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
