<?php
require '../vendor/autoload.php'; // include dompdf autoload if installed via composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// HTML Content (your A4 form)
$html = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Form Layout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      table-layout: fixed;
    }
    td {
      border: 1px solid black;
      vertical-align: top;
      padding: 6px;
      font-size: 14px;
      word-wrap: break-word;
    }
    .col-1 {
      width: 25%;
      font-weight: bold;
      background: #f9f9f9;
    }
    .col-2 {
      width: 25%;
      border-left: none; /* remove left border */
    }
    .col-3 {
      width: 50%;
    }
    .section-header {
      background: #f1f1f1;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <table>
    <!-- 01 Date -->
    <tr>
      <td class="col-1 section-header">01 Date</td>
      <td class="col-2">Date</td>
      <td class="col-3"></td>
    </tr>

    <!-- 02 Sales Employee -->
    <tr>
      <td class="col-1 section-header" rowspan="3">02 Sales Employee</td>
      <td class="col-2">DSE / CSE Name</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">RSM / ASM Name</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">FSM Name</td>
      <td class="col-3"></td>
    </tr>

    <!-- 03 Distribution -->
    <tr>
      <td class="col-1 section-header" rowspan="3">03 Distribution</td>
      <td class="col-2">Distributor Name</td>
      <td class="col-3">ANSEN Gas Distributors (Pvt) Ltd</td>
    </tr>
    <tr>
      <td class="col-2">Direct Operation</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">Type</td>
      <td class="col-3">Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐</td>
    </tr>

    <!-- 04 Customer -->
    <tr>
      <td class="col-1 section-header" rowspan="6">04 Customer</td>
      <td class="col-2">Customer Code</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">Customer Name</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">Owner / Manager</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">Address</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">Contact No</td>
      <td class="col-3"></td>
    </tr>
    <tr>
      <td class="col-2">ID No</td>
      <td class="col-3"></td>
    </tr>
  </table>

</body>
</html>
';

// Load HTML
$dompdf->loadHtml($html);

// Setup paper size
$dompdf->setPaper('A4', 'portrait');

// Render to PDF
$dompdf->render();

// Output to browser
$dompdf->stream("trust_cylinders_form.pdf", ["Attachment" => false]);
