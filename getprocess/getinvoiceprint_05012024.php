<?php
session_start();
require_once('../connection/db.php');

$recordID=$_POST['recordID'];

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
        <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logoprint.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="4" class="text-center small align-middle">
                        <h2 class="font-weight-light m-0">Ansen Gas Distributors (Pvt) Ltd</h2>
                        65, Archbishop Nicholas Marcus Fernando Mawatha, Negombo<br>
                        Tel: 0094-31-4549149 | Fax: 0094-31-2225050 info@ansengas.lk<br>
                        <span class="font-weight-bold">Distributor for LAUGFS Gas PLC.</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>             
        </table>  
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">Invoice: INV-<?php echo $rowinvoiceinfo['idtbl_invoice'] ?></div>
    <div class="col-12">Customer Name: <?php echo $rowinvoiceinfo['name'] ?></div>
    <div class="col-12">Address: <?php echo $rowinvoiceinfo['address'] ?></div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>New</th>
                    <th>Refill</th>
                    <th>Empty</th>
                    <th class="text-right">Sale Price</th>
                    <th class="text-right">Refill Price</th>
                    <th class="text-right">Empty Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while($rowinvoicedetail=$resultinvoicedetail->fetch_assoc()){
                        $totrefill=$rowinvoicedetail['newqty']*$rowinvoicedetail['newprice'];
                        $tottrust=$rowinvoicedetail['refillqty']*$rowinvoicedetail['refillprice'];
                        $totnew=$rowinvoicedetail['emptyqty']*$rowinvoicedetail['emptyprice'];
                        $total=number_format(($totrefill+$totnew+$tottrust), 2);
                ?>
                <tr>
                    <td><?php echo $rowinvoicedetail['product_name']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['newqty']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['refillqty']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['emptyqty']; ?></td>
                    <td class="text-right"><?php echo number_format($rowinvoicedetail['newprice'],2); ?></td>
                    <td class="text-right"><?php echo number_format($rowinvoicedetail['refillprice'],2); ?></td>
                    <td class="text-right"><?php echo number_format($rowinvoicedetail['emptyprice'],2); ?></td>
                    <td class="text-right"><?php echo $total; ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-left">Net Total</th>
                    <th class="text-right"><?php echo number_format($rowinvoiceinfo['total'], 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <h6>Payment Mode</h6>
        <table class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint border-0">
            <thead>
                <tr>
                    <th>Cash</th>
                    <th>&nbsp;</th>
                    <th>Credit</th>
                    <th>&nbsp;</th>
                    <th>Cheque</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>No</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>Bank</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="row mt-4">
    <div class="col-4 text-center">...........................................................<br>Sig. of Driver</div>
    <div class="col-4 text-center">...........................................................<br>Company Seal</div>
    <div class="col-4 text-center">...........................................................<br>Sig. of Customer</div>
</div>
<div class="row mt-4">
    <div class="col-8 text-right">Name :</div>
    <div class="col-4">..................................................................</div>
</div>
<div class="row">
    <div class="col-8 text-right">ID No :</div>
    <div class="col-4">..................................................................</div>
</div>