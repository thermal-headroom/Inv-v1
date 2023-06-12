<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Get the POST data
$client_name = $_POST['client_name'];
$invoice_number = $_POST['invoice_number'];

// Perform validation here...

// Save the data to a JSON file
$data = [
  'client_name' => $client_name,
  'invoice_number' => $invoice_number,
  // add other fields here
];

// Format the file name with the date and invoice number
$json_file_name = 'invoices/' . date('Y-m-d') . '-' . $invoice_number . '.json';
file_put_contents($json_file_name, json_encode($data));

// Load the Excel template
$templatePath = 'path_to_your_template.xlsx';
$spreadsheet = IOFactory::load($templatePath);

// Fill in the data
$worksheet = $spreadsheet->getActiveSheet();
$worksheet->getCell('A1')->setValue($client_name);
$worksheet->getCell('B1')->setValue($invoice_number);

// Save your filled-in template as a new file
$excel_file_name = 'invoices/' . date('Y-m-d') . '-' . $invoice_number . '.xlsx';
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($excel_file_name);

// Redirect to the invoices page
header('Location: view_invoices.php');
