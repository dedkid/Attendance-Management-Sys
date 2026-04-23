<?php
include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;



$spreadsheet = new Spreadsheet();

// Retrieve the start_date and end_date values from the query parameters
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Employees sheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Employees');

$name = $_GET['name'];

$timein_sql = "SELECT u.name, COALESCE(ei.department, ii.department) AS department, COALESCE(ei.work_hrs, ii.work_hrs) AS work_hrs, u.position, t.datetime AS timein, o.datetime AS timeout, n.type, n.date
FROM users u
LEFT JOIN emp_info ei ON u.name = ei.name AND u.position = 'Employee'
LEFT JOIN int_info ii ON u.name = ii.name AND u.position = 'Intern'
LEFT JOIN time_in t ON u.name = t.name
LEFT JOIN time_out o ON u.name = o.name
LEFT JOIN notices n ON u.name = n.name
WHERE u.name = '$name'
ORDER BY t.datetime ASC";



$timein_result = mysqli_query($conn, $timein_sql);

$sheet->setCellValue('A2', 'Name');
$sheet->setCellValue('B2', 'Department');
$sheet->setCellValue('C2', 'Position');
$sheet->setCellValue('D2', 'Schedule');

// Apply borders to the headers
$sheet->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$row = 3;
$dates = array(); // Store unique dates
$nameTracker = array(); // Track names
$timeinData = array(); // Store time in data for each name
$noticeData = array(); // Store notice data for each name and date



while ($rowIn = mysqli_fetch_assoc($timein_result)) {
   
    $name = $rowIn['name'];
    $date = $rowIn['timein'] ? date('Y-m-d', strtotime($rowIn['timein'])) : '';
    $timein = $rowIn['timein'] ? date('H:i:s', strtotime($rowIn['timein'])) : '';
    $timeout = $rowIn['timeout'] ? date('H:i:s', strtotime($rowIn['timeout'])) : '';

    // Check if date exists in the dates array and matches the start and end date conditions
    if ($date >= $start_date && $date <= $end_date) {
        if (!in_array($date, $dates) && $date !== '') {
            $dates[] = $date;
        }

        // Store time in and time out data for each name and date
        $timeinData[$name][$date] = array(
            'timein' => $timein,
            'timeout' => $timeout
        );
    }

    // Print the name only if it hasn't been printed before
    if (!in_array($name, $nameTracker)) {
        $sheet->setCellValue('A' . $row, $name);
        $sheet->setCellValue('B' . $row, $rowIn['department']);
        $sheet->setCellValue('C' . $row, $rowIn['position']);
        $sheet->setCellValue('D' . $row, $rowIn['work_hrs']);

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

        
        // Add the notice date to the dates array if it doesn't exist and falls within the desired date range
        if ($noticeDate >= $start_date && $noticeDate <= $end_date) {
            if (!in_array($noticeDate, $dates)) {
                $dates[] = $noticeDate;
            }
        }

    }

    $row++;
}

sort($dates);
// Set headers for merged date columns
$columnIndex = 5;
foreach ($dates as $date) {
    // Skip the date if it's outside the desired range
    if ($date < $start_date || $date > $end_date) {
        continue;
    }
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
foreach ($nameTracker as $name) {
    $columnIndex = 5;
    foreach ($dates as $date) {
        $timein = isset($timeinData[$name][$date]['timein']) ? $timeinData[$name][$date]['timein'] : '';
        $timeout = isset($timeinData[$name][$date]['timeout']) ? $timeinData[$name][$date]['timeout'] : '';

        // Check if the Ti value is "PL" and replace the cell value with "PL" instead of filling it with green color
        if ($timein == 'PL') {
            $sheet->setCellValueByColumnAndRow($columnIndex, $row, 'PL');
        } elseif ($timein == 'SIL') {
            $sheet->setCellValueByColumnAndRow($columnIndex, $row, 'SIL');
        } elseif ($timein == 'SL') {
            $sheet->setCellValueByColumnAndRow($columnIndex, $row, 'SL');
        } elseif ($timein == 'ABW') {
            $sheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
        } elseif ($timein == 'L') {
            $sheet->setCellValueByColumnAndRow($columnIndex, $row, 'L');
        } elseif ($timein == '?') {
            $sheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        } else {
            // Set the fill color of the cell in the Ti column to green if time in is not empty
            if (!empty($timein)) {
                // Get the time portion from the timein value
                $time = strtotime($timein);
                $timeFormatted = date('H:i', $time);
        
                // Check if the time is past 8:01
                if ($timeFormatted >= '08:01') {
                    $sheet->setCellValueByColumnAndRow($columnIndex, $row, 'L');
                } else {
                    $sheet->getStyleByColumnAndRow($columnIndex, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
                }
            }
        }

        // Check if the To value is "PL" and replace the cell value with "PL" instead of filling it with green color
        if ($timeout == 'PL') {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'PL');
        } elseif ($timeout == 'SIL') {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'SIL');
        } elseif ($timeout == 'SL') {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $row, 'SL');
        } elseif ($timeout == 'ABW') {
            $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
        } elseif ($timeout == 'L') {
            $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        } elseif ($timeout == '?') {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $row, '?');
        } else {
            // Set the fill color of the cell in the To column to green if time out is not empty
            if (!empty($timeout)) {
                $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
            }
        }

        // Center the content in the time in and time out cells
        $sheet->getStyleByColumnAndRow($columnIndex, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnIndex, $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Set border style for the time in and time out cells
        $sheet->getStyleByColumnAndRow($columnIndex, $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyleByColumnAndRow($columnIndex + 1, $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $columnIndex += 2;
    }
    $row++;
}


// Set the height of row A
$sheet->getRowDimension(1)->setRowHeight(65);
// Auto-size the columns
foreach (range('A', $sheet->getHighestColumn()) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Time In/Out Record.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
