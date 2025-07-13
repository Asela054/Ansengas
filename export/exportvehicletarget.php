<?php
include '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

if (isset($_POST["file_content"])) {
    $temporary_html_file = 'tmp_html/' . time() . '.html';

    // Create the tmp_html directory if it doesn't exist
    if (!is_dir('tmp_html')) {
        mkdir('tmp_html', 0775, true);
    }

    file_put_contents($temporary_html_file, $_POST["file_content"]);

    $reader = IOFactory::createReader('Html');
    $spreadsheet = $reader->load($temporary_html_file);

    // Define border style
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
    ];

    // Define bold font style for headings
    $boldStyle = [
        'font' => [
            'bold' => true,
        ],
    ];

    // Get active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Apply border style to all cells with data
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $cellRange = 'A1:' . $highestColumn . $highestRow;
    
    $sheet->getStyle($cellRange)->applyFromArray($borderStyle);
    
    // Apply bold style to the first row (table headings)
    $headingRange = 'A1:' . $highestColumn . '1';
    $sheet->getStyle($headingRange)->applyFromArray($boldStyle);

    // Convert and format cells with comma-separated values
    for ($row = 1; $row <= $highestRow; $row++) {
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cell = $sheet->getCell($col . $row);
            $value = $cell->getValue();

            // Check if the value contains a comma
            if (strpos($value, ',') !== false) {
                // Remove commas from the value
                $cleanValue = str_replace(',', '', $value);

                // If the cleaned value is numeric, convert it to a number and apply number formatting
                if (is_numeric($cleanValue)) {
                    $cell->setValueExplicit((float)$cleanValue, DataType::TYPE_NUMERIC);
                    // Apply number format with 2 decimal places
                    $sheet->getStyle($col . $row)
                          ->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                }
            }
        }
    }

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    $filename = 'vehicletarget.xlsx';

    $writer->save($filename);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Transfer-Encoding: Binary');
    header("Content-disposition: attachment; filename=\"".$filename."\"");

    readfile($filename);

    unlink($temporary_html_file);
    unlink($filename);

    exit;
}

header("Location:../employeetarget.php");
?>