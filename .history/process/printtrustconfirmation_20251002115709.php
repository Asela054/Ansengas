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
    <!-- Empty -->
  </td>
</tr>

<tr>
  <!-- 1st Column -->
  <td rowspan="7" style="border: 1px solid #000; border-right: none; font-weight: bold; width: 25%; padding: 3px;">
    04 Customer
  </td>
  
  <!-- 2nd Column -->
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Customer Code:
  </td>
  
  <!-- 3rd Column -->
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Customer Name:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Owner / Manager:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Address:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Contact No:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    ID No:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    <!-- Empty -->
  </td>
</tr>

<tr>
  <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: 1px solid #000; padding: 3px; height: 18px;">
    Type:
  </td>
  <td style="border: 1px solid #000; border-left: none; padding: 3px; height: 18px;">
    Commercial ☐ &nbsp; Dealer ☐ &nbsp; Distributor ☐ &nbsp; Other ☐
  </td>
</tr>

<tr>
  <td style="border: 1px solid #000; border-right: none; font-weight: bold; padding: 6px;">Stock – Cylinders on Trust</td>
  <td colspan="2" style="border: 1px solid #000; padding: 0;">
    <table style="border-collapse: collapse; width: 100%;">
      <tr>
        <td style="border: 1px solid #000; text-align: center; padding: 4px;" rowspan="2">Stock – Cylinders on Trust</td>
        <td style="border: 1px solid #000; text-align: center; padding: 4px;">12.5</td>
        <td style="border: 1px solid #000; text-align: center; padding: 4px;">37.5</td>
      </tr>
      <tr>
        <td style="border: 1px solid #000; text-align: center; padding: 4px; height: 40px;"></td>
        <td style="border: 1px solid #000; text-align: center; padding: 4px; height: 40px;"></td>
      </tr>
    </table>
  </td>
</tr>

<tr>
  <td style="border: 1px solid #000; border-right: none; font-weight: bold; padding: 6px;">05 Details of Trust cylinders-Info.</td>
  <td colspan="2" style="border: 1px solid #000; height:160px; padding: 6px;">
    I/We, the undersigned, ..................................................... of .....................................................<br><br>
    hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC and I/We guarantee to hand over said number of Cylinders on demand and in a failure to handover same I/We hereby undertake to pay the total value of the said LPG Cylinders on a demand made by LAUGFS Gas PLC (at the prevailing value of an empty Cylinder). We hereby admit that LAUGFS Gas PLC and its authorized representatives have the authority to enter into my/our premises and take possession of the said number of Gas Cylinders with prior notice to me/us.
  </td>
</tr>

<tr>
  <td colspan="3" style="border: 1px solid #000; height:90px; padding: 6px;">
    I/we hereby confirm that the above cylinders which are the property of LAUGFS Gas PLC is in my/our possession as at the given date, and hereby take full responsibility for such cylinders, and shall take steps to return the same to LAUGFS Gas PLC in good order upon request.
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
