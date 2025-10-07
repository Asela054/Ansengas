<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// HTML layout
$html = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>PDF Form</title>
  <style>
    @page {
      size: A4;
      margin: 20px;
    }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td {
      border: 1px solid #000;
      padding: 6px;
      vertical-align: top;
    }
    /* remove left border for first column */
    td:first-child {
      border-left: none;
      font-weight: bold;
      width: 25%;
    }
    td:nth-child(2) {
      width: 25%;
    }
    td:nth-child(3) {
      width: 50%;
    }
    .section-header {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <table>
    <tr>
      <td class="section-header">01 Date</td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td class="section-header">02 Sales Employee</td>
      <td>
        DSE / CSE Name<br><br>
        RSM / ASM Name<br><br>
        FSM Name
      </td>
      <td></td>
    </tr>

    <tr>
      <td class="section-header">03 Distribution</td>
      <td>
        Distributor Name: ANSEN Gas Distributors (Pvt) Ltd<br><br>
        Direct Operation:<br><br>
        Type: Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐
      </td>
      <td></td>
    </tr>

    <tr>
      <td class="section-header">04 Customer</td>
      <td>
        Customer Code:<br><br>
        Customer Name:<br><br>
        Owner / Manager:<br><br>
        Address:<br><br>
        Contact No:<br><br>
        ID No:
      </td>
      <td></td>
    </tr>
  </table>

</body>
</html>
';

// Load and render
$dompdf->loadHtml($html);
$dompdf->render();

// Output to browser
$dompdf->stream("form.pdf", ["Attachment" => false]);
