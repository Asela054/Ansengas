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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LAUGFS Gas PLC - Trust Cylinders Confirmation Form</title>
  <style>
    @page { size: A4; margin: 20mm; }
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; }
    td { border: 1px solid #000; padding: 6px; vertical-align: top; }
    td:first-child { font-weight: bold; width: 25%; }
    td:nth-child(2) { width: 25%; border-left: none; padding: 0; }
    td:nth-child(3) { width: 50%; }
    .title { text-align: center; font-weight: bold; font-size: 14px; padding: 8px; border: none; }
    .inner-table { border-collapse: collapse; width: 100%; }
    .inner-table td { border: 1px solid #000; padding: 6px; }
    .sub-table { border-collapse: collapse; width: 100%; }
    .sub-table td { border: 1px solid #000; padding: 4px; text-align: center; }
  </style>
</head>
<body>

  <table>
    <tr>
      <td colspan="3" class="title">
        LAUGFS Gas PLC <br>
        Trust Cylinders Confirmation Form
      </td>
    </tr>

    <tr>
      <td>01 Date</td>
      <td>
        <table class="inner-table">
          <tr><td>Date</td></tr>
        </table>
      </td>
      <td></td>
    </tr>

    <tr>
      <td>02 Sales Employee</td>
      <td>
        <table class="inner-table">
          <tr><td>DSE / CSE Name</td></tr>
          <tr><td>RSM / ASM Name</td></tr>
          <tr><td>FSM Name</td></tr>
        </table>
      </td>
      <td></td>
    </tr>

    <tr>
      <td>03 Distribution</td>
      <td>
        <table class="inner-table">
          <tr><td>Distributor Name: ANSEN Gas Distributors (Pvt) Ltd</td></tr>
          <tr><td>Direct Operation:</td></tr>
          <tr><td>Type: Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐</td></tr>
        </table>
      </td>
      <td></td>
    </tr>

    <tr>
      <td>04 Customer</td>
      <td>
        <table class="inner-table">
          <tr><td>Customer Code:</td></tr>
          <tr><td>Customer Name:</td></tr>
          <tr><td>Owner / Manager:</td></tr>
          <tr><td>Address:</td></tr>
          <tr><td>Contact No:</td></tr>
          <tr><td>ID No:</td></tr>
        </table>
      </td>
      <td></td>
    </tr>

    <tr>
      <td>Stock – Cylinders on Trust</td>
      <td colspan="2">
        <table class="sub-table">
          <tr>
            <td rowspan="2">Stock – Cylinders on Trust</td>
            <td colspan="2">LAUGFS</td>
          </tr>
          <tr>
            <td>12.5</td>
            <td>37.5</td>
          </tr>
          <tr>
            <td style="height:40px;"></td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td>05 Details of Trust cylinders-Info.</td>
      <td colspan="2" style="height:160px;">
        I/We, the undersigned, ..................................................... of .....................................................<br><br>
        hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC and I/We guarantee to hand over said number of Cylinders on demand and in a failure to handover same I/We hereby undertake to pay the total value of the said LPG Cylinders on a demand made by LAUGFS Gas PLC (at the prevailing value of an empty Cylinder). We hereby admit that LAUGFS Gas PLC and its authorized representatives have the authority to enter into my/our premises and take possession of the said number of Gas Cylinders with prior notice to me/us.
      </td>
    </tr>

    <tr>
      <td colspan="3" style="height:90px;">
        I/we hereby confirm that the above cylinders which are the property of LAUGFS Gas PLC is in my/our possession as at the given date, and hereby take full responsibility for such cylinders, and shall take steps to return the same to LAUGFS Gas PLC in good order upon request.
      </td>
    </tr>

    <tr>
      <td>06 Trust Cylinders confirmation</td>
      <td colspan="2">
        <table width="100%" class="inner-table">
          <tr>
            <td>
              Signature of the Customer<br><br>
              ..................................<br>
              Company Seal<br>
              Date
            </td>
            <td>
              Signature of the Distributor<br><br>
              ..................................<br>
              Company Seal<br>
              Date
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td colspan="3" style="height:120px;">
        AUTHORIZED BY<br><br>
        ASM: ....................................<br><br>
        FSM: ....................................<br><br>
        Authorized Signatory (Auditor): ....................................<br><br>
        Date: ....................................
      </td>
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
