<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$employee=$_POST['employee'];
$customer=$_POST['customer'];
$area=$_POST['area'];

$thismonth = date("n", strtotime($validfrom));
$thisyear = date("Y", strtotime($validfrom)); 

$dayscount=cal_days_in_month(CAL_GREGORIAN,$thismonth,$thisyear);

?>
<table class="table table-striped table-bordered table-sm small" id="refsaletable">
    <thead>
        <tr>
            <th rowspan="2">Date</th>
            <th colspan="4">Qty Sold-Re Filled</th>
            <th colspan="4">Qty Sold-New</th>
        </tr>
        <tr>
            <th class="tdcount text-center">2KG</th>
            <th class="tdcount text-center">5KG</th>
            <th class="tdcount text-center">12.5KG</th>
            <th class="tdcount text-center">37.5KG</th>
            <th class="tdcount text-center">2KG</th>
            <th class="tdcount text-center">5KG</th>
            <th class="tdcount text-center">12.5KG</th>
            <th class="tdcount text-center">37.5KG</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        for($i=1; $i<=$dayscount; $i++){ 
            $salearray=array();

            $invdate=date("Y-m-", strtotime($validfrom)).$i;
            $arrayproduct=array('6','4','1','2');
            foreach($arrayproduct as $rowproduct){
                if($customer && $employee != ''){
                    $sqlcylinder="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invdate' AND `ref_id`='$employee' AND `tbl_customer_idtbl_customer`='$customer') AND `tbl_product_idtbl_product`='$rowproduct' AND `status`=1";
                }elseif($area && $employee != ''){
                    $sqlcylinder="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invdate' AND `ref_id`='$employee' AND `tbl_area_idtbl_area`='$area') AND `tbl_product_idtbl_product`='$rowproduct' AND `status`=1";
                }else{
                    $sqlcylinder="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invdate' AND `ref_id`='$employee') AND `tbl_product_idtbl_product`='$rowproduct' AND `status`=1";
                }
                $resultcylinder=$conn->query($sqlcylinder);
                $rowcylinder=$resultcylinder->fetch_assoc();

                $objnew=new stdClass();
                $objnew->newqty=$rowcylinder['newqty'];
                $objnew->refillqty=$rowcylinder['refillqty'];

                array_push($salearray, $objnew);
            }
        ?>
        <tr>
            <td><?php $dateObj   = DateTime::createFromFormat('!m', $thismonth); $monthName = $dateObj->format('M');echo $thisyear.'-'.$monthName.'-'.$i; ?></td>
            <?php foreach($salearray as $rownewsale){ ?>
            <td class="text-center"><?php echo $rownewsale->newqty; ?></td>
            <?php } foreach($salearray as $rowrefillsale){ ?>
            <td class="text-center"><?php echo $rowrefillsale->refillqty; ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
$(document).ready(function() {
    $('#refsaletable thead .tdcount').each(function(i) {
        // console.log(i);
        calculateColumn(i);
    });
});
function calculateColumn(index) {
    var total = 0;
    var index = parseInt(index)+1;
    $('#refsaletable tbody tr').each(function() {
        var value = parseInt($('td', this).eq(index).text());
        if (!isNaN(value)) {
            total += value;
        }
    });
    // console.log(total);
    $('#refsaletable tfoot th').eq(index).text(total);
}
</script>