<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

// Initialize DOMPDF
$dompdf = new Dompdf();

$html = '
<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td {
      vertical-align: top;
      padding: 5px;
    }
    .section-header {
      width: 20%;
      font-weight: bold;
      border-top: 1px solid black;
      border-bottom: 1px solid black;
      border-right: 1px solid black;
    }
    .section-content {
      width: 60%;
      border-top: 1px solid black;
      border-bottom: 1px solid black;
      border-right: 1px solid black;
    }
    .section-empty {
      width: 20%;
      border-top: 1px solid black;
      border-bottom: 1px solid black;
      border-right: 1px solid black;
    }
    .sub-row {
      border-bottom: 1px solid black;
      padding: 5px 0;
    }
    .sub-row:last-child {
      border-bottom: none;
    }
  </style>
</head>
<body>

<table>
  <tr>
    <td class="section-header">01 Date</td>
    <td class="section-content">
      <div class="sub-row">Date:</div>
    </td>
    <td class="section-empty"></td>
  </tr>

  <tr>
    <td class="section-header">02 Sales Employee</td>
    <td class="section-content">
      <div class="sub-row">DSE / CSE Name</div>
      <div class="sub-row">RSM / ASM Name</div>
      <div class="sub-row">FSM Name</div>
    </td>
    <td class="section-empty"></td>
  </tr>

  <tr>
    <td class="section-header">03 Distribution</td>
    <td class="section-content">
      <div class="sub-row">Distributor Name: ANSEN Gas Distributors (Pvt) Ltd</div>
      <div class="sub-row">Direct Operation:</div>
      <div class="sub-row">Type: Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐</div>
    </td>
    <td class="section-empty"></td>
  </tr>

  <tr>
    <td class="section-header">04 Customer</td>
    <td class="section-content">
      <div class="sub-row">Customer Code:</div>
      <div class="sub-row">Customer Name:</div>
      <div class="sub-row">Owner / Manager:</div>
      <div class="sub-row">Address:</div>
      <div class="sub-row">Contact No:</div>
      <div class="sub-row">ID No:</div>
    </td>
    <td class="section-empty"></td>
  </tr>
</table>

</body>
</html>
';

// Load into DOMPDF
$dompdf->loadHtml($html);

// Set paper size to A4 portrait
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output to browser
$dompdf->stream("form.pdf", ["Attachment" => false]);
