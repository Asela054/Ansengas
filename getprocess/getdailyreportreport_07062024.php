<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$advance=$_POST['advance'];
$lorry=$_POST['lorry'];

if($advance==0){
    $sqldaily="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_customer`.`name` AS `cusname`, `tbl_employee`.`name` AS `refname`, `tbl_vehicle`.`vehicleno`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`  LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_vehicle_load`.`lorryid` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`date`='$validfrom' AND `tbl_vehicle_load`.`lorryid`='$lorry' AND `tbl_invoice`.`status`=1";
    $resultdaily =$conn-> query($sqldaily);
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Date</th>
            <th>Area</th>
            <th>Customer</th>
            <th>Executive</th>
            <th>Vehicle</th>
            <th class="text-right">Invoice Total</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($rowdaily = $resultdaily-> fetch_assoc()){ ?>
        <tr>
        <td>
            <?php 
                if ($rowdaily['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowdaily['idtbl_invoice'];
                } else {
                    echo 'AGT'.$rowdaily['tax_invoice_num'];
                }
            ?>
            </td>
            <td><?php echo $rowdaily['date']; ?></td>
            <td><?php echo $rowdaily['area']; ?></td>
            <td><?php echo $rowdaily['cusname']; ?></td>
            <td><?php echo $rowdaily['refname']; ?></td>
            <td><?php echo $rowdaily['vehicleno']; ?></td>
            <td class="text-right"><?php echo number_format($rowdaily['nettotal'], 2); ?></td>
            <td class="text-center"><button class="btn btn-outline-dark btn-sm viewbtninv" id="<?php echo $rowdaily['idtbl_invoice'] ?>"><i class="fas fa-eye"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php 
} else { 
    $sqldaily="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_customer`.`name` AS `cusname`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`discount_status`, `tbl_employee`.`name` AS `refname`, `tbl_vehicle`.`vehicleno`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_vehicle_load`.`lorryid` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`date`='$validfrom'  AND `tbl_vehicle_load`.`lorryid`='$lorry' AND `tbl_invoice`.`status`=1";
    $resultdaily =$conn-> query($sqldaily); 

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatamount = $rowvat['vat'];


    $accessoriesarray=array();
    $sqlaccessories="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=2";
    $resultaccessories =$conn-> query($sqlaccessories);   
    while($rowaccessories = $resultaccessories-> fetch_assoc()){
        $obj = new stdClass();
        $obj->accessID=$rowaccessories['idtbl_product'];
        $obj->access=$rowaccessories['product_name'];

        array_push($accessoriesarray, $obj);
    }
?>
    <table class="table table-striped table-bordered table-sm text-center">
        <thead>
            <tr>
                <th nowrap rowspan="2" class="text-left align-top">Invoice</th>
                <th nowrap rowspan="2" class="text-left align-top">Customer</th>
                <th nowrap colspan="4">2 KG</th>
                <th nowrap colspan="4">5 KG</th>
                <th nowrap colspan="4">12.5 KG</th>
                <th nowrap colspan="4">37.5 KG</th>
                <?php if (!empty($accessoriesarray)) { ?>
                    <th nowrap colspan="<?php echo count($accessoriesarray); ?>">Accessories</th>
                <?php } ?>
                <th nowrap rowspan="2" class="text-right align-bottom">Cash</th>
                <th nowrap class="text-center">Cheque Detail</th>
                <th nowrap rowspan="2" class="text-right align-bottom">Credit</th>
                <th nowrap rowspan="2" class="text-right align-bottom">Discount Amount</th>
            </tr>
            <tr>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>Trust</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>Trust</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>Trust</th>
                <th nowrap>New</th>
                <th nowrap>Refill</th>
                <th nowrap>Empty</th>
                <th nowrap>Trust</th>
                <?php foreach($accessoriesarray as $rowaccessoriesarray){ ?>
                <th nowrap><?php echo $rowaccessoriesarray->access ?></th>
                <?php } ?>
                <th nowrap class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $ponenewqty=0;
            $ponerefillqty=0;
            $poneemptyqty=0;
            $ponetrustqty=0;

            $ptwonewqty=0;
            $ptworefillqty=0;
            $ptwoemptyqty=0;
            $ptwotrustqty=0;

            $pthreenewqty=0;
            $pthreerefillqty=0;
            $pthreeemptyqty=0;
            $pthreetrustqty=0;

            $pfournewqty=0;
            $pfourrefillqty=0;
            $pfouremptyqty=0;
            $pfourtrustqty=0;

            $totalamount=0;
            $totalcash=0;
            $totalcheque=0;
            $totalcredit=0;
            $total_discount = 0;

            if($resultdaily->num_rows>0){while($rowdaily = $resultdaily-> fetch_assoc()){ 
                $invoiceID=$rowdaily['idtbl_invoice'];
                $customerID=$rowdaily['idtbl_customer'];
                $discountstatus=$rowdaily['discount_status'];

                $sqlinvdetail="SELECT `refillqty`, `encustomer_refillprice`, `tbl_product_idtbl_product`, `discount_price` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID' AND `status`=1 AND `tbl_product_idtbl_product`=1";
                $resultinvdetail = $conn->query($sqlinvdetail);
                $rowinvdetail = $resultinvdetail->fetch_assoc();

                if(!empty($rowinvdetail['tbl_product_idtbl_product']) && $rowinvdetail['discount_price']>0){
                    $refillqty=$rowinvdetail['refillqty'];
                    $refill_price=(($rowinvdetail['encustomer_refillprice']*($vatamount+100))/100);
                    $discount_price=(($rowinvdetail['discount_price']*($vatamount+100))/100);

                    $total_refillprice=$refill_price*$refillqty;
                    $total_discountprice=$discount_price*$refillqty;

                    $discount_amount=$total_refillprice-$total_discountprice;
                }
                else{
                    $discount_amount=0;
                }      

                $sqlproductone="SELECT `newqty`, `refillqty`, `emptyqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='6' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductone =$conn-> query($sqlproductone);   
                $rowproductone = $resultproductone-> fetch_assoc();

                $sqlproducttwo="SELECT `newqty`, `refillqty`, `emptyqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='4' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproducttwo =$conn-> query($sqlproducttwo);   
                $rowproducttwo = $resultproducttwo-> fetch_assoc();

                $sqlproductthree="SELECT `newqty`, `refillqty`, `emptyqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='1' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductthree =$conn-> query($sqlproductthree);   
                $rowproductthree = $resultproductthree-> fetch_assoc();

                $sqlproductfour="SELECT `newqty`, `refillqty`, `emptyqty`, `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='2' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                $resultproductfour =$conn-> query($sqlproductfour);   
                $rowproductfour = $resultproductfour-> fetch_assoc();

                $sqlcash="SELECT SUM(`amount`) AS `amount` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=1 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
                $resultcash =$conn-> query($sqlcash);   
                $rowcash = $resultcash-> fetch_assoc();

                $chequelist='';
                $chequetotal=0;
                $i=1;
                $sqlcheque="SELECT `amount`, `chequeno` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=2 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
                $resultcheque =$conn-> query($sqlcheque);   
                while($rowcheque = $resultcheque-> fetch_assoc()){
                    $chequelist.=$rowcheque['chequeno'];
                    if($i<$resultcheque->num_rows){
                        $chequelist.='/';
                    }

                    $chequetotal=$chequetotal+$rowcheque['amount'];
                    $i++;
                }

                if ($discountstatus==1) {
                    $discountamount=$discount_amount;
                }
                else{
                    $discount_amount=0;
                }
            ?>
            <tr>
                <td nowrap class="text-left">
                <?php 
                    if ($rowdaily['tax_invoice_num'] == null) {
                        echo 'INV-'.$rowdaily['idtbl_invoice'];
                    } else {
                        echo 'AGT'.$rowdaily['tax_invoice_num'];
                    }
                ?>
                </td>
                <td nowrap class="text-left"><?php echo $rowdaily['cusname']; ?></td>
                <td nowrap><?php echo $rowproductone['newqty']; $ponenewqty=$ponenewqty+$rowproductone['newqty']; ?></td>
                <td nowrap><?php echo $rowproductone['refillqty']; $ponerefillqty=$ponerefillqty+$rowproductone['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductone['emptyqty']; $poneemptyqty=$poneemptyqty+$rowproductone['emptyqty']; ?></td>
                <td nowrap><?php echo $rowproductone['trustqty']; $ponetrustqty=$ponetrustqty+$rowproductone['trustqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['newqty']; $ptwonewqty=$ptwonewqty+$rowproducttwo['newqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['refillqty']; $ptworefillqty=$ptworefillqty+$rowproducttwo['refillqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['emptyqty']; $ptwoemptyqty=$ptwoemptyqty+$rowproducttwo['emptyqty']; ?></td>
                <td nowrap><?php echo $rowproducttwo['trustqty']; $ptwotrustqty=$ptwotrustqty+$rowproducttwo['trustqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['newqty']; $pthreenewqty=$pthreenewqty+$rowproductthree['newqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['refillqty']; $pthreerefillqty=$pthreerefillqty+$rowproductthree['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['emptyqty']; $pthreeemptyqty=$pthreeemptyqty+$rowproductthree['emptyqty']; ?></td>
                <td nowrap><?php echo $rowproductthree['trustqty']; $pthreetrustqty=$pthreetrustqty+$rowproductthree['trustqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['newqty']; $pfournewqty=$pfournewqty+$rowproductfour['newqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['refillqty']; $pfourrefillqty=$pfourrefillqty+$rowproductfour['refillqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['emptyqty']; $pfouremptyqty=$pfouremptyqty+$rowproductfour['emptyqty']; ?></td>
                <td nowrap><?php echo $rowproductfour['trustqty']; $pfourtrustqty=$pfourtrustqty+$rowproductfour['trustqty']; ?></td>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;

                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                ?>
                <td nowrap><?php echo $rowaccessoriesqty['newqty'] ?></td>
                <?php } ?>
                <td nowrap class="text-right"><?php echo number_format($rowcash['amount'],2); $totalcash=$totalcash+$rowcash['amount']; ?></td>
                <td nowrap class="text-right"><?php echo number_format($chequetotal,2); $totalcheque=$totalcheque+$chequetotal; ?></td>
                <td nowrap class="text-right">
                    <?php 
                        echo number_format(($rowdaily['nettotal']-($discount_amount+$rowcash['amount']+$chequetotal)),2);  
                        $totalcredit=$totalcredit+($rowdaily['nettotal']-($discount_amount+$rowcash['amount']+$chequetotal)); 
                    ?>
                </td>
                <td class="text-right"><?php echo number_format($discount_amount, 2); $total_discount+=$discount_amount; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th>&nbsp;</th>
                <th class="text-right">Total :</th></th>
                <th><?php echo $ponenewqty; ?></th>
                <th><?php echo $ponerefillqty; ?></th>
                <th><?php echo $poneemptyqty; ?></th>
                <th><?php echo $ponetrustqty; ?></th>
                <th><?php echo $ptwonewqty; ?></th>
                <th><?php echo $ptworefillqty; ?></th>
                <th><?php echo $ptwoemptyqty; ?></th>
                <th><?php echo $ptwotrustqty; ?></th>
                <th><?php echo $pthreenewqty; ?></th>
                <th><?php echo $pthreerefillqty; ?></th>
                <th><?php echo $pthreeemptyqty; ?></th>
                <th><?php echo $pthreetrustqty; ?></th>
                <th><?php echo $pfournewqty; ?></th>
                <th><?php echo $pfourrefillqty; ?></th>
                <th><?php echo $pfouremptyqty; ?></th>
                <th><?php echo $pfourtrustqty; ?></th>
                <?php 
                foreach($accessoriesarray as $rowaccessoriesarray){ 
                    $accessoriesID=$rowaccessoriesarray->access;
                    
                    $sqlaccessoriesqty="SELECT `newqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$accessoriesID' AND `tbl_invoice_idtbl_invoice`='$invoiceID'";
                    $resultaccessoriesqty =$conn-> query($sqlaccessoriesqty);   
                    $rowaccessoriesqty = $resultaccessoriesqty-> fetch_assoc();    
                    ?>
                <th nowrap>&nbsp;</th>
                <?php } ?>
                <!-- <th class="text-right"><?php // echo number_format($totalamount, 2); ?></th> -->
                <th class="text-right"><?php echo number_format($totalcash, 2); ?></th>
                <th class="text-right"><?php echo number_format($totalcheque, 2); ?></th>
                <th class="text-right"><?php echo number_format($totalcredit, 2); ?></th>
                <th class="text-right"><?php echo number_format($total_discount, 2); ?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>