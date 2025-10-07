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
    td, th { border: 1px solid #000; padding: 6px; vertical-align: top; }
    .title { text-align: center; font-weight: bold; font-size: 14px; padding: 8px; border: none; }
    .section-header { width: 180px; font-weight: bold; }
    .sub-table { border-collapse: collapse; width: 100%; }
    .sub-table td { border: 1px solid #000; padding: 4px; text-align: center; }
  </style>
</head>
<body>

  <table>
    <tr>
      <td colspan="2" class="title">
        LAUGFS Gas PLC <br>
        Trust Cylinders Confirmation Form
      </td>
    </tr>

    <tr>
      <td class="section-header">01 Date</td>
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
        Direct Operation: <br><br>
        Type: Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐
      </td>
    </tr>

    <tr>
      <td class="section-header">04 Customer</td>
      <td>
        Customer Code: <br><br>
        Customer Name: <br><br>
        Owner / Manager: <br><br>
        Address: <br><br>
        Contact No: <br><br>
        ID No:
      </td>
    </tr>

    <tr>
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
      <td class="section-header">05 Details of Trust cylinders-Info.</td>
      <td style="height:160px;">
        I/We, the undersigned, ..................................................... of .....................................................<br><br>
        hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC and I/We guarantee to hand over said number of Cylinders on demand and in a failure to handover same I/We hereby undertake to pay the total value of the said LPG Cylinders on a demand made by LAUGFS Gas PLC (at the prevailing value of an empty Cylinder). We hereby admit that LAUGFS Gas PLC and its authorized representatives have the authority to enter into my/our premises and take possession of the said number of Gas Cylinders with prior notice to me/us.
      </td>
    </tr>

    <tr>
      <td colspan="2" style="height:90px;">
        I/we hereby confirm that the above cylinders which are the property of LAUGFS Gas PLC is in my/our possession as at the given date, and hereby take full responsibility for such cylinders, and shall take steps to return the same to LAUGFS Gas PLC in good order upon request.
      </td>
    </tr>

    <tr>
      <td class="section-header">06 Trust Cylinders confirmation</td>
      <td>
        <table width="100%">
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
      <td colspan="2" style="height:120px;">
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
