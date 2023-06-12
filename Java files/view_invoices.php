<?php

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

// Create CSV
$csv_file_name = $invoice_dir . '/' . date('d') . '-' . $invoice_number . '.csv';
$file = fopen($csv_file_name, 'w');
if (!$file) {
    die('Failed to open CSV file for writing.');
}

fputcsv($file, ['Client Name', 'Invoice Number']);
fputcsv($file, [$client_name, $invoice_number]);
fclose($file);

// Redirect to the invoices page
header('Location: view_invoices.php');
