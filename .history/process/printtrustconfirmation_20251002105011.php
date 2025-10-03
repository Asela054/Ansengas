<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAUGFS Gas PLC - Trust Cylinders Confirmation Form</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }
        
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .form-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .form-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .form-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f5f5f5;
        }
        
        .checkbox-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .declaration-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            background-color: #f9f9f9;
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-box {
            width: 30%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin: 40px 0 10px;
        }
        
        .signature-label {
            font-size: 14px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .form-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div class="company-name">LAUGFS Gas PLC</div>
            <div class="form-title">Trust Cylinders Confirmation Form</div>
        </div>
        
        <!-- Section 01: Date -->
        <div class="section">
            <div class="section-title">01 Date</div>
            <table class="form-table">
                <tr>
                    <td class="label">DSE / CSE Name</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <!-- Section 02: Sales Employee -->
        <div class="section">
            <div class="section-title">02 Sales Employee</div>
            <table class="form-table">
                <tr>
                    <td class="label">RSM / ASM Name</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">FSM Name</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <!-- Section 03: Distribution -->
        <div class="section">
            <div class="section-title">03 Distribution</div>
            <table class="form-table">
                <tr>
                    <td class="label">Distributor Name</td>
                    <td>ANSEN Gas Distributors (Pvt) Ltd</td>
                </tr>
                <tr>
                    <td class="label">Direct Operation</td>
                    <td></td>
                </tr>
            </table>
            
            <div class="checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="type-commercial">
                    <label for="type-commercial">Commercial</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="type-dealer">
                    <label for="type-dealer">Dealer</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="type-distributor">
                    <label for="type-distributor">Distributor</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="type-other">
                    <label for="type-other">Other</label>
                </div>
            </div>
            
            <table class="form-table">
                <tr>
                    <td class="label">Customer Code</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <!-- Section 04: Customer -->
        <div class="section">
            <div class="section-title">04 Customer</div>
            <table class="form-table">
                <tr>
                    <td class="label">Customer Name</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">Owner / Manager</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">Address</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">Contact No</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">ID No</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <!-- Section 05: Details of Trust cylinders-info -->
        <div class="section">
            <div class="section-title">05 Details of Trust cylinders-info</div>
            <table class="form-table">
                <tr>
                    <td class="label">LAUGFS</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">12.5</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">37.5</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="label">Stock – Cylinders on Trust</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <!-- Declaration -->
        <div class="declaration-box">
            <p>I/We, the undersigned, ...... of hereby irrevocably declare that above mentioned Cylinders delivered by LAUGFS Gas PLC are the properties of LAUGFS Gas PLC and I/We guarantee to hand over said number of Cylinders on demand and in a failure to handover same I/We hereby undertake to pay the total value of the said LPG Cylinders on a demand made by LAUGFS Gas PLC (at the prevailing value of an empty Cylinder) We hereby admit that LAUGFS Gas PLC and its authorized representatives have the authority to enter into my/our premises and take possession of the said number of Gas Cylinders with prior notice to me/us.</p>
        </div>
        
        <!-- Section 06: Trust Cylinders confirmation -->
        <div class="section">
            <div class="section-title">06 Trust Cylinders confirmation</div>
            <p>I/we hereby confirm that the above cylinders which are the property of LAUGFS Gas PLC is in my/our possession as at the given date,and hereby take full responsibility for such cylinders,and shall take steps to return the same to LAUGFS Gas PLC in good order upon request.</p>
        </div>
        
        <!-- Signature Area -->
        <div class="signature-area">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Signature of the Customer</div>
                <div class="signature-label">Company Seal</div>
                <div class="signature-label">Date</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Signature of the Distributor</div>
                <div class="signature-label">Company Seal</div>
                <div class="signature-label">Date</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Authorized signatory (Auditor)</div>
                <div class="signature-label">Date</div>
            </div>
        </div>
    </div>
</body>
</html>