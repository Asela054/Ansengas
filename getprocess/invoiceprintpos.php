<?php
session_start();
require_once('../connection/db.php');

$cashier=$_SESSION['name'];
$recordID=$_GET['recordID'];

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_customer`.`address` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`encustomer_newprice`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`encustomer_emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Cutive+Mono&display=swap" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <title>Invoice</title>
    <!-- <style media="print">
        * {
            font-family: 'Fira Mono', monospace;
        }
        table,tr,th,td{
            font-family: 'Fira Mono', monospace;
        }
        img{
            width:200px;
            height:100px;
        }
    </style>
    <style>
        * {
            font-family: 'Fira Mono', monospace;
        }
        table,tr,th,td{
            font-family: 'Fira Mono', monospace;
        }
        img{
            width:100px;
            height:100px;
        }
    </style> -->
    <style media="print">
        * {
            font-family: 'Cutive Mono', monospace;
            font-weight: 600;
        }
        table,tr,th,td{
            font-family: 'Cutive Mono', monospace;
            font-weight: 600;
        }
        img{
            width:200px;
            height:100px;
        }
    </style>
    <style>
        * {
            font-family: 'Cutive Mono', monospace;
            font-weight: 600;
        }
        table,tr,th,td{
            font-family: 'Cutive Mono', monospace;
            font-weight: 600;
        }
        img{
            width:100px;
            height:100px;
        }
    </style>
</head>

<body>
    <?php //print_r($invoiceproduct->result()); ?>
    <div id='DivIdToPrint'>
        <table style="width:100%;">
            <tr>
                <td style="text-align: center; font-size:16px;border-bottom:1px dotted black;" colspan="2">
                    <h2 class="font-weight-light" style="margin-top:0;margin-bottom:0;">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                    65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka,<br>
                    Tel: 0312 235 050
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;">Date</td>
                <td style="text-align: right; font-size:14px;"><?php echo $rowinvoiceinfo['date'] ?></td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;">Invoice No.</td>
                <td style="text-align: right; font-size:14px;">INV-<?php echo $rowinvoiceinfo['idtbl_invoice'] ?></td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Cashier</td>
                <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;"><?php echo $cashier; ?></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="2">
                    <table style="width:100%;">
                        <tr>
                            <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Name</td>
                            <td style="text-align: center; font-size:14px;border-bottom:1px dotted black;">Price</td>
                            <td style="text-align: center; font-size:14px;border-bottom:1px dotted black;">Qty</td>
                            <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Total</td>
                        </tr>
                        <?php 
                        $itemtotal = 0;
                        while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
                            $saleprice = $rowinvoicedetail['encustomer_newprice'] ?: $rowinvoicedetail['encustomer_refillprice'] ?: $rowinvoicedetail['encustomer_emptyprice'];
                            $qty = $rowinvoicedetail['newqty'] ?: $rowinvoicedetail['refillqty'] ?: $rowinvoicedetail['emptyqty'];
                            $totamount = $qty * $saleprice;
                            $total = number_format($totamount, 2);
                        ?>
                            <tr>
                                <td style="text-align: left; font-size:14px;" colspan="4"><?php echo $rowinvoicedetail['product_name']; ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size:14px;">&nbsp;</td>
                                <td style="text-align: center; font-size:14px;"><?php echo number_format($saleprice, 2); ?></td>
                                <td style="text-align: center; font-size:14px;"><?php echo $qty; ?></td>
                                <td style="text-align: right; font-size:14px;"><?php echo $total; ?></td>
                            </tr>
                        <?php 
                            $itemtotal++;
                        }
                        ?>
                        
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:16px;font-weight: bold;">Net Total</td>
                <td style="text-align: right; font-size:16px;font-weight: bold;"><?php echo number_format($rowinvoiceinfo['nettotal'], 2) ?></td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Total Item Count </td>
                <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;"><?php echo $itemtotal; ?></td>
            </tr>
            <!-- <tr>
                <td style="text-align: justify; font-size:10px;" colspan="2">In case of price discrepancy, return the item & bll within 7 days for refund of difference. Check the product warranty with shop owner.</td>
            </tr> -->
            <tr>
                <td style="text-align: center; font-size:10px;" colspan="2"><span style="text-align: center; font-size:16px;font-weight: bold;">Thank You. Come again</span><br><i style="text-align: center; font-size:16px;" class='lab la-facebook'></i> ANSEN GAS DISTRIBUTORS (PVT) LTD  / Copyright © ERav Technology</td>
            </tr>
        </table>
    </div>
    <!-- <p>Do not print.</p>
    <button type='button' id='btn'>Print</button> -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 5000);
    </script>
</body>

</html>
