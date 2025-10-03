<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LAUGFS Gas PLC - Trust Cylinders Confirmation Form</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 12px;">

<table style="border-collapse: collapse; width: 100%;">
<tr>
  <td colspan="3" style="text-align: center; font-weight: bold; font-size: 14px; padding: 8px; border: none;">
    LAUGFS Gas PLC <br>
    Trust Cylinders Confirmation Form
  </td>
</tr>

<tr>
  <td style="border: 1px solid #000; border-right: none; font-weight: bold; width: 25%; padding: 6px;">01 Date</td>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; width: 25%; padding: 6px;"></td>
  <td style="border: 1px solid #000; border-left: none; width: 50%; padding: 6px;"></td>
</tr>

<tr>
  <td rowspan="3" style="border: 1px solid #000; border-right: none; font-weight: bold; width: 25%; padding: 3px;">
    02 Sales Employee
  </td>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    DSE / CSE Name
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    RSM / ASM Name
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;"></td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    FSM Name
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;"></td>
</tr>


<tr>
  <!-- 1st Column -->
  <td rowspan="2" style="border: 1px solid #000; border-right: none; font-weight: bold; width: 25%; padding: 3px;">
    03 Distribution
  </td>
  
  <!-- 2nd Column -->
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Distributor Name
  </td>
  
  <!-- 3rd Column -->
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    ANSEN Gas Distributors (Pvt) Ltd
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Direct Operation:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td rowspan="7" style="border: 1px solid #000; border-right: none; font-weight: bold; width: 25%; padding: 3px;">
    04 Customer
  </td>
  
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Type:
  </td>
  
    <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <table style="width:100%; border-collapse: collapse;">
        <tr>
        <td style="width:25%; border:none; padding:0; text-align:center;">Commercial</td>
        <td style="width:25%; border:none; padding:0; text-align:center;">Dealer</td>
        <td style="width:25%; border:none; padding:0; text-align:center;">Distributor</td>
        <td style="width:25%; border:none; padding:0; text-align:center;">Other</td>
        </tr>
    </table>
    </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Customer Code:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Customer Name:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Owner / Manager:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Address:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Contact No:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    ID No:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
  </td>
</tr>

<tr>
  <td colspan="3" style="border: 1px solid #000; padding: 0;">
    <table style="width: 100%; border-collapse: collapse;">
      <!-- First row -->
      <tr>
        <td rowspan="5" style="border: 1px solid #000; font-weight: bold; width: 50%; padding: 3px; vertical-align: middle;">
          05 Details of Trust Cylinders-Info.
        </td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 50%;">LAUGFS</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 30%;"></td>
      </tr>

      <!-- Second row with 4 columns -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 35%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 12.5%;">12.5</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 37.5%;">37.5</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 35%;"></td>
      </tr>

      <!-- Third row -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 35%;">Stock – Cylinders on Trust</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 37.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 35%;"></td>
      </tr>

      <!-- Fourth row -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 35%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 37.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 35%;"></td>
      </tr>

      <!-- Fifth row: Paragraph -->
      <tr>
        <td colspan="4" style="border: 1px solid #000; padding: 6px; vertical-align: top; height: 160px;">
          I/We, the undersigned, ..................................................... of .....................................................<br><br>
          hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC...
        </td>
      </tr>
    </table>
  </td>
</tr>


<tr>
  <td style="border: 1px solid #000; border-right: none; font-weight: bold; padding: 6px;">06 Trust Cylinders confirmation</td>
  <td colspan="2" style="border: 1px solid #000; padding: 0;">
    <table style="border-collapse: collapse; width: 100%;">
      <tr>
        <td style="border: 1px solid #000; padding: 4px;">
          Signature of the Customer<br><br>
          ..................................<br>
          Company Seal<br>
          Date
        </td>
        <td style="border: 1px solid #000; padding: 4px;">
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
  <td colspan="3" style="border: 1px solid #000; height:120px; padding: 6px;">
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

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("trust_cylinders_form.pdf", ["Attachment" => false]);
