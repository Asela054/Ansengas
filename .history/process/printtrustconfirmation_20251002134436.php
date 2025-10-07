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
    <td colspan="3" 
        style="text-align: center; font-weight: bold; font-size: 14px; padding: 8px; 
               border: 1px solid #000; background-color: #f0f0f0;">
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
      <!-- First row: LAUGFS wider -->
      <tr>
        <td rowspan="5" style="border: 1px solid #000; font-weight: bold; width: 24%; padding: 3px; vertical-align: middle;">
          05 Details of Trust Cylinders-Info.
        </td>
        <td colspan="3" style="border: 1px solid #000; padding: 3px 50px 3px 3px; height: 18px; text-align: right; width: 40%;">LAUGFS</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
      </tr>

      <!-- Second row: middle two equal width -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 25%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 12.5%;">12.5</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 12.5%;">37.5</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 25%;"></td>
      </tr>

      <!-- Third row: equal widths like second row -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; text-align: center; width: 25%;">Stock – Cylinders on Trust</td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 25%;"></td>
      </tr>

      <!-- Fourth row: equal widths -->
      <tr>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 25%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 12.5%;"></td>
        <td style="border: 1px solid #000; padding: 3px; height: 18px; width: 25%;"></td>
      </tr>

      <!-- Fifth row: Paragraph -->
      <tr>
        <td colspan="4" style="border: 1px solid #000; padding: 6px; vertical-align: top; height: 160px;">
          I/We, the undersigned, ..................................................................................................... of ...........................................................................................................................................<br><br>
          hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC and I/We guarantee to hand over said number of Cylinders on demand and in a failure to handover same I/We hereby undertake to pay the total value of the said LPG Cylinders on a demand made by LAUGFS Gas PLC (at the prevailing value of an empty Cylinder). We hereby admit that LAUGFS Gas PLC and its authorized representatives have the authority to enter into my/our premises and take possession of the said number of Gas Cylinders with prior notice to me/us.
        </td>
      </tr>
    </table>
  </td>
</tr>


<!-- Trust Cylinders Section -->
<tr>
  <!-- Left column: title -->
  <td style="border: 1px solid #000; border-right: none; font-weight: bold; padding: 6px; vertical-align: top;">
    06 Trust Cylinders confirmation
  </td>

  <!-- Right column: paragraph + signature table -->
  <td colspan="2" style="border: 1px solid #000; padding: 6px; vertical-align: top;">
    <!-- Paragraph -->
    <p style="margin:0 0 15px 0; height: 50px; vertical-align: top;">
      I/we hereby confirm that the above cylinders which are the property of LAUGFS Gas PLC is in my/our possession as at the given date, and hereby take full responsibility for such cylinders, and shall take steps to return the same to LAUGFS Gas PLC in good order upon request.
    </p>

    <!-- Signature table with only middle vertical border -->
    <table style="border-collapse: collapse; width: 100%; margin-top: 10px;">
      <tr>
        <!-- Signature of Customer -->
        <td style="padding: 8px; width: 50%; border-right: 1px solid #000; vertical-align: top;">
          <div style="margin-bottom: 25px;">
            <div style="border-bottom: 1px dotted #000; padding-bottom: 4px; margin-bottom: 4px;"></div>
            <div>Signature of the Customer</div>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div style="flex: 1; margin-right: 15px;">
              <div style="padding-top: 10px; margin-top: 10px;"></div>
              <div>Company Seal</div>
            </div>
            <div style="text-align: right;">
              <div style="padding-bottom: 4px; margin-bottom: 4px; width: 100px;"></div>
              <div style="border-top: 1px dotted #000;">Date</div>
            </div>
          </div>
        </td>

        <!-- Signature of Distributor -->
        <td style="padding: 8px; width: 50%; vertical-align: top;">
          <div style="margin-bottom: 25px;">
            <div style="border-bottom: 1px dotted #000; padding-bottom: 4px; margin-bottom: 4px;"></div>
            <div>Signature of the Distributor</div>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div style="flex: 1; margin-right: 15px;">
              <div style="padding-top: 10px; margin-top: 10px;"></div>
              <div>Company Seal</div>
            </div>
            <div style="text-align: right;">
              <div style="padding-bottom: 4px; margin-bottom: 4px; width: 100px;"></div>
              <div style="border-top: 1px dotted #000;">Date</div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </td>
</tr>

<tr>
    <td style="border: 1px solid #000; height: 120px; padding: 6px; width: 0%;">
      <!-- Empty Cell -->
    </td>
    <td style="border: 1px solid #000; height: 120px; padding: 6px; width: 50%; vertical-align: top;">
      AUTHORIZED BY<br><br>
      <table style="border-collapse: collapse; width: 100%;">
        <tr>
          <td style="width: 50%; vertical-align: top;">
            <div style="border-top: 1px dotted #000; width: 100%; padding-top: 4px;">ASM</div>
          </td>
          <td style="width: 50%; vertical-align: top;">
            <div style="border-top: 1px dotted #000; width: 100%; padding-top: 4px;">FSM</div>
          </td>
        </tr>
      </table>
    </td>
    <td style="border: 1px solid #000; height: 120px; padding: 6px; width: 50%; vertical-align: top;">
      <table style="border-collapse: collapse; width: 100%;">
        <tr>
          <td style="width: 50%; vertical-align: top;">
            <div style="border-top: 1px dotted #000; width: 100%; padding-top: 4px;">Authorized Signatory (Auditor)</div>
          </td>
          <td style="width: 50%; vertical-align: top;">
            <div style="border-top: 1px dotted #000; width: 100%; padding-top: 4px;">Date</div>
          </td>
        </tr>
      </table>
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
