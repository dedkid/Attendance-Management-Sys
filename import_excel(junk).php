<?php
include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excel_file"])) {
    $file = $_FILES["excel_file"];
    $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);

    if ($fileExtension == 'xlsx' || $fileExtension == 'xls') {
        $tmpName = $file["tmp_name"];
        $spreadsheet = IOFactory::load($tmpName);
        $worksheet = $spreadsheet->getActiveSheet();

        $timein_records = [];
        $timeout_records = [];

        // Iterate over the rows and extract data
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            if (count($rowData) === 3) {
                // Time In record
                $timein_records[] = $rowData;
            } elseif (count($rowData) === 4) {
                // Time Out record
                $timeout_records[] = $rowData;
            }
        }

        // Prepare and execute SQL statements for Time In records
        $timein_sql = "INSERT INTO time_in (name, datetime, location) VALUES (?, ?, ?)";
        $timein_stmt = $conn->prepare($timein_sql);
        foreach ($timein_records as $record) {
            $timein_stmt->bind_param("sss", $record[0], $record[1], $record[2]);
            $timein_stmt->execute();
        }

        // Prepare and execute SQL statements for Time Out records
        $timeout_sql = "INSERT INTO time_out (name, datetime, overtime, hours) VALUES (?, ?, ?, ?)";
        $timeout_stmt = $conn->prepare($timeout_sql);
        foreach ($timeout_records as $record) {
            $timeout_stmt->bind_param("ssss", $record[0], $record[1], $record[2], $record[3]);
            $timeout_stmt->execute();
        }

        $timein_stmt->close();
        $timeout_stmt->close();

        echo 'Data imported successfully!';
    } else {
        echo 'Invalid file format. Only XLSX and XLS files are allowed.';
    }
}
?>