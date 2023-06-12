<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Get the POST data
$client_name = trim($_POST['client_name']);
$invoice_number = $_POST['invoice_number'];

// Perform validation
if (empty($client_name)) {
    die('Client name is required.');
}

if (!is_numeric($invoice_number) || $invoice_number <= 0) {
    die('Invoice number must be a positive number.');
}

// Save the data to a JSON file
$data = [
  'client_name' => $client_name,
  'invoice_number' => $invoice_number,
  // add other fields here
];

// Format the directory and file names with the date and invoice number
$invoice_dir = 'invoices/' . date('Y/m');
if (!file_exists($invoice_dir) && !mkdir($invoice_dir, 0755, true)) {
    die('Failed to create invoice directory.');
}

$json_file_name = $invoice_dir . '/' . date('d') . '-' . $invoice_number . '.json';
if (file_put_contents($json_file_name, json_encode($data)) === false) {
    die('Failed to write to JSON file.');
}

// Load the Excel template
$templatePath = 'path_to_your_template.xlsx';
try {
    $spreadsheet = IOFactory::load($templatePath);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    die('Failed to load Excel template: ' . $e->getMessage());
}

// Fill in the data
$worksheet = $spreadsheet->getActiveSheet();
$worksheet->getCell('A1')->setValue($client_name);
$worksheet->getCell('B1')->setValue($invoice_number);

// Save your filled-in template as a new file
$excel_file_name = $invoice_dir . '/' . date('d') . '-' . $invoice_number . '.xlsx';
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
try {
    $writer->save($excel_file_name);
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    die('Failed to write to Excel file: ' . $e->getMessage());
}

// Redirect to the invoices page
header('Location: view_invoices.php');
