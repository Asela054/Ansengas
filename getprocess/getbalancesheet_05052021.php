<?php 
require_once('../connection/db.php');

$noncurrenttot=0;
$currenttot=0;
$equitytot=0;
$currentliabilitiestot=0;
$noncurrentbanktot=0;

$param_date_fr=$_POST['param_date_fr'];
$param_date_to=$_POST['param_date_to'];
$branch=1;

$sqlmaster="SELECT tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master FROM tbl_master INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year WHERE tbl_master.tbl_company_branch_idtbl_company_branch='$branch' AND tbl_master.status=1";
$resultmaster=$conn->query($sqlmaster);
$rowmaster=$resultmaster->fetch_assoc();

$idtbl_master=$rowmaster['idtbl_master'];

$sqlheadsection="SELECT * FROM `tbl_gl_report_head_sections` WHERE `report_id`='BAL'";
$resultheadsection=$conn->query($sqlheadsection);

while($rowheadsection=$resultheadsection->fetch_assoc()){
    $headid=$rowheadsection['id'];

    $sqlaccount="SELECT `tbl_gl_report_sub_sections`.`sub_section_name`, `tbl_gl_report_sub_section_particulars`.`idtbl_subaccount`, `tbl_gl_report_sub_section_particulars`.`subaccount` FROM `tbl_gl_report_sub_sections` LEFT JOIN `tbl_gl_report_sub_section_particulars` ON `tbl_gl_report_sub_section_particulars`.`tbl_gl_report_sub_section_id`=`tbl_gl_report_sub_sections`.`id` WHERE `tbl_gl_report_sub_sections`.`tbl_gl_report_head_section_id`='$headid' AND `tbl_gl_report_sub_sections`.`sect_cancel`=0";
    $resultaccount=$conn->query($sqlaccount);
?>
<tr>
    <th class="font-weight-bold"><?php echo $rowheadsection['head_section_name'] ?></th>
    <td></td>
    <td></td>
    <td></td>
</tr>
<?php 
$i=1;
while($rowaccount=$resultaccount->fetch_assoc()){ 
    $accountnumber=$rowaccount['subaccount'];
    $accountid=$rowaccount['idtbl_subaccount'];

    if($headid==5){
        $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
        $resultbal=$conn->query($sqlbal);
        $rowbal=$resultbal->fetch_assoc();

        $noncurrenttot=$noncurrenttot+$rowbal['accamount'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td><?php echo $rowbal['accname'] ?></td>
        <td class="text-right"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right"><?php if($resultaccount->num_rows==$i){echo number_format($noncurrenttot, 2);} ?></th>
    </tr>
<?php 
    }
    if($headid==6){
        if(empty($accountid)){
            $sqlclosestock="SELECT SUM(tbl_stock.fullqty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.fullqty>0";
            $resultclosestock=$conn->query($sqlclosestock);
            $rowclosestock=$resultclosestock->fetch_assoc();
            
            $currenttot=$currenttot+$rowclosestock['stock_close_value'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td>&nbsp;</td>
        <td class="text-right"><?php echo number_format($rowclosestock['stock_close_value'], 2); ?></td>
        <th class="text-right"></th>
    </tr>
<?php 
        }
        else{
            $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
            $resultbal=$conn->query($sqlbal);
            $rowbal=$resultbal->fetch_assoc();

            $currenttot=$currenttot+$rowbal['accamount'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td><?php echo $rowbal['accname'] ?></td>
        <td class="text-right"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right <?php if($resultaccount->num_rows==$i){echo 'border-dark border-right-0';}?>"><?php if($resultaccount->num_rows==$i){echo number_format($currenttot, 2);} ?></th>
    </tr>
<?php   
        }
        if($resultaccount->num_rows==$i){
?>
    <tr>
        <td class="pl-4">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="text-right">&nbsp;</td>
        <th class="text-right border-dark border-right-0"><?php echo number_format($noncurrenttot+$currenttot, 2); ?></th>
    </tr>
<?php
        }
    }
    if($headid==7){
        $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
        $resultbal=$conn->query($sqlbal);
        $rowbal=$resultbal->fetch_assoc();

        $equitytot=$equitytot+$rowbal['accamount'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td><?php echo $rowbal['accname'] ?></td>
        <td class="text-right"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right"><?php if($resultaccount->num_rows==$i){echo number_format($equitytot, 2);} ?></th>
    </tr>
<?php 
    }
    if($headid==8){
        $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
        $resultbal=$conn->query($sqlbal);
        $rowbal=$resultbal->fetch_assoc();

        $currentliabilitiestot=$currentliabilitiestot+$rowbal['accamount'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td><?php echo $rowbal['accname'] ?></td>
        <td class="text-right"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right"><?php if($resultaccount->num_rows==$i){echo number_format($currentliabilitiestot, 2);} ?></th>
    </tr>
<?php 
    }
    if($headid==9){
        $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
        $resultbal=$conn->query($sqlbal);
        $rowbal=$resultbal->fetch_assoc();

        $noncurrentbanktot=$noncurrentbanktot+$rowbal['accamount'];
?>
    <tr>
        <td class="pl-4"><?php echo $rowaccount['sub_section_name'] ?></td>
        <td><?php echo $rowbal['accname'] ?></td>
        <td class="text-right"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right <?php if($resultaccount->num_rows==$i){echo 'border-dark border-right-0';}?>"><?php if($resultaccount->num_rows==$i){echo number_format($noncurrentbanktot, 2);} ?></th>
    </tr>
<?php 
    } 
    if($resultaccount->num_rows==$i && $headid==9){
?>
    <tr>
        <td class="pl-4">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="text-right">&nbsp;</td>
        <th class="text-right <?php if($resultaccount->num_rows==$i){echo 'border-dark border-right-0';}?>"><?php echo number_format($equitytot+$currentliabilitiestot+$noncurrentbanktot, 2); ?></th>
    </tr>
<?php } $i++;}} ?>