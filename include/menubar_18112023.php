<?php 
$getUrl=$_SERVER['SCRIPT_NAME'];
$url=explode('/', $getUrl);
$lastElement=end($url);

if($lastElement=='useraccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 1, 1);
    $editcheck=checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 1, 4);
}
else if($lastElement=='usertype.php'){
    $addcheck=checkprivilege($menuprivilegearray, 2, 1);
    $editcheck=checkprivilege($menuprivilegearray, 2, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 2, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 2, 4);
}
else if($lastElement=='userprivilege.php'){
    $addcheck=checkprivilege($menuprivilegearray, 3, 1);
    $editcheck=checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 3, 4);
}
else if($lastElement=='customer.php'){
    $addcheck=checkprivilege($menuprivilegearray, 4, 1);
    $editcheck=checkprivilege($menuprivilegearray, 4, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 4, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 4, 4);
}
else if($lastElement=='loading.php'){
    $addcheck=checkprivilege($menuprivilegearray, 5, 1);
    $editcheck=checkprivilege($menuprivilegearray, 5, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 5, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 5, 4);
}
else if($lastElement=='grn.php'){
    $addcheck=checkprivilege($menuprivilegearray, 6, 1);
    $editcheck=checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 6, 4);
}
else if($lastElement=='porder.php'){
    $addcheck=checkprivilege($menuprivilegearray, 7, 1);
    $editcheck=checkprivilege($menuprivilegearray, 7, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 7, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 7, 4);
}
else if($lastElement=='productcategory.php'){
    $addcheck=checkprivilege($menuprivilegearray, 8, 1);
    $editcheck=checkprivilege($menuprivilegearray, 8, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 8, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 8, 4);
}
else if($lastElement=='product.php'){
    $addcheck=checkprivilege($menuprivilegearray, 9, 1);
    $editcheck=checkprivilege($menuprivilegearray, 9, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 9, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 9, 4);
}
else if($lastElement=='stock.php'){
    $addcheck=checkprivilege($menuprivilegearray, 10, 1);
    $editcheck=checkprivilege($menuprivilegearray, 10, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 10, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 10, 4);
}
else if($lastElement=='vehicle.php'){
    $addcheck=checkprivilege($menuprivilegearray, 11, 1);
    $editcheck=checkprivilege($menuprivilegearray, 11, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 11, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 11, 4);
}
else if($lastElement=='employee.php'){
    $addcheck=checkprivilege($menuprivilegearray, 12, 1);
    $editcheck=checkprivilege($menuprivilegearray, 12, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 12, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 12, 4);
}
else if($lastElement=='area.php'){
    $addcheck=checkprivilege($menuprivilegearray, 13, 1);
    $editcheck=checkprivilege($menuprivilegearray, 13, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 13, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 13, 4);
}
else if($lastElement=='invoice.php'){
    $addcheck=checkprivilege($menuprivilegearray, 14, 1);
    $editcheck=checkprivilege($menuprivilegearray, 14, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 14, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 14, 4);
}
else if($lastElement=='invoicepayment.php'){
    $addcheck=checkprivilege($menuprivilegearray, 15, 1);
    $editcheck=checkprivilege($menuprivilegearray, 15, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 15, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 15, 4);
}
else if($lastElement=='paymentreceipt.php'){
    $addcheck=checkprivilege($menuprivilegearray, 16, 1);
    $editcheck=checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 16, 4);
}
else if($lastElement=='unloading.php'){
    $addcheck=checkprivilege($menuprivilegearray, 17, 1);
    $editcheck=checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 17, 4);
}
else if($lastElement=='stock.php'){
    $addcheck=checkprivilege($menuprivilegearray, 18, 1);
    $editcheck=checkprivilege($menuprivilegearray, 18, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 18, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 18, 4);
}
else if($lastElement=='employeetarget.php'){
    $addcheck=checkprivilege($menuprivilegearray, 19, 1);
    $editcheck=checkprivilege($menuprivilegearray, 19, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 19, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 19, 4);
}
else if($lastElement=='vehicletarget.php'){
    $addcheck=checkprivilege($menuprivilegearray, 20, 1);
    $editcheck=checkprivilege($menuprivilegearray, 20, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 20, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 20, 4);
}
else if($lastElement=='customeroutstanding.php'){
    $addcheck=checkprivilege($menuprivilegearray, 21, 1);
    $editcheck=checkprivilege($menuprivilegearray, 21, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 21, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 21, 4);
}
else if($lastElement=='dailysale.php'){
    $addcheck=checkprivilege($menuprivilegearray, 22, 1);
    $editcheck=checkprivilege($menuprivilegearray, 22, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 22, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 22, 4);
}
else if($lastElement=='employeetargetadd.php'){
    $addcheck=checkprivilege($menuprivilegearray, 23, 1);
    $editcheck=checkprivilege($menuprivilegearray, 23, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 23, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 23, 4);
}
else if($lastElement=='vehicletargetadd.php'){
    $addcheck=checkprivilege($menuprivilegearray, 24, 1);
    $editcheck=checkprivilege($menuprivilegearray, 24, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 24, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 24, 4);
}
else if($lastElement=='refsaleinfo.php'){
    $addcheck=checkprivilege($menuprivilegearray, 25, 1);
    $editcheck=checkprivilege($menuprivilegearray, 25, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 25, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 25, 4);
}
else if($lastElement=='invoiceview.php'){
    $addcheck=checkprivilege($menuprivilegearray, 26, 1);
    $editcheck=checkprivilege($menuprivilegearray, 26, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 26, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 26, 4);
}
else if($lastElement=='invoicerecovery.php'){
    $addcheck=checkprivilege($menuprivilegearray, 27, 1);
    $editcheck=checkprivilege($menuprivilegearray, 27, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 27, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 27, 4);
}
else if($lastElement=='bank-info.php'){
    $addcheck=checkprivilege($menuprivilegearray, 28, 1);
    $editcheck=checkprivilege($menuprivilegearray, 28, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 28, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 28, 4);
}
else if($lastElement=='branch-info.php'){
    $addcheck=checkprivilege($menuprivilegearray, 29, 1);
    $editcheck=checkprivilege($menuprivilegearray, 29, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 29, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 29, 4);
}
else if($lastElement=='account-mainclass.php'){
    $addcheck=checkprivilege($menuprivilegearray, 30, 1);
    $editcheck=checkprivilege($menuprivilegearray, 30, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 30, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 30, 4);
}
else if($lastElement=='account-subclass.php'){
    $addcheck=checkprivilege($menuprivilegearray, 31, 1);
    $editcheck=checkprivilege($menuprivilegearray, 31, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 31, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 31, 4);
}
else if($lastElement=='account-mainaccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 32, 1);
    $editcheck=checkprivilege($menuprivilegearray, 32, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 32, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 32, 4);
}
else if($lastElement=='account-subaccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 33, 1);
    $editcheck=checkprivilege($menuprivilegearray, 33, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 33, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 33, 4);
}
else if($lastElement=='financialyear.php'){
    $addcheck=checkprivilege($menuprivilegearray, 34, 1);
    $editcheck=checkprivilege($menuprivilegearray, 34, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 34, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 34, 4);
}
else if($lastElement=='account-allocation.php'){
    $addcheck=checkprivilege($menuprivilegearray, 35, 1);
    $editcheck=checkprivilege($menuprivilegearray, 35, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 35, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 35, 4);
}
else if($lastElement=='account-allocationtobankbranch.php'){
    $addcheck=checkprivilege($menuprivilegearray, 36, 1);
    $editcheck=checkprivilege($menuprivilegearray, 36, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 36, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 36, 4);
}
else if($lastElement=='account-block.php'){
    $addcheck=checkprivilege($menuprivilegearray, 37, 1);
    $editcheck=checkprivilege($menuprivilegearray, 37, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 37, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 37, 4);
}
else if($lastElement=='dailycash.php'){
    $addcheck=checkprivilege($menuprivilegearray, 38, 1);
    $editcheck=checkprivilege($menuprivilegearray, 38, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 38, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 38, 4);
}
else if($lastElement=='salereportcustomer.php'){
    $addcheck=checkprivilege($menuprivilegearray, 39, 1);
    $editcheck=checkprivilege($menuprivilegearray, 39, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 39, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 39, 4);
}
else if($lastElement=='salereportproduct.php'){
    $addcheck=checkprivilege($menuprivilegearray, 40, 1);
    $editcheck=checkprivilege($menuprivilegearray, 40, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 40, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 40, 4);
}
else if($lastElement=='bufferstockmaintainreport.php'){
    $addcheck=checkprivilege($menuprivilegearray, 41, 1);
    $editcheck=checkprivilege($menuprivilegearray, 41, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 41, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 41, 4);
}
else if($lastElement=='damagereturn.php'){
    $addcheck=checkprivilege($menuprivilegearray, 42, 1);
    $editcheck=checkprivilege($menuprivilegearray, 42, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 42, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 42, 4);
}
else if($lastElement=='company.php'){
    $addcheck=checkprivilege($menuprivilegearray, 43, 1);
    $editcheck=checkprivilege($menuprivilegearray, 43, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 43, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 43, 4);
}
else if($lastElement=='companybranch.php'){
    $addcheck=checkprivilege($menuprivilegearray, 44, 1);
    $editcheck=checkprivilege($menuprivilegearray, 44, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 44, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 44, 4);
}
else if($lastElement=='chequeinfo.php'){
    $addcheck=checkprivilege($menuprivilegearray, 45, 1);
    $editcheck=checkprivilege($menuprivilegearray, 45, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 45, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 45, 4);
}
else if($lastElement=='bankaccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 46, 1);
    $editcheck=checkprivilege($menuprivilegearray, 46, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 46, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 46, 4);
}
else if($lastElement=='paymenttype.php'){
    $addcheck=checkprivilege($menuprivilegearray, 47, 1);
    $editcheck=checkprivilege($menuprivilegearray, 47, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 47, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 47, 4);
}
else if($lastElement=='payment.php'){
    $addcheck=checkprivilege($menuprivilegearray, 48, 1);
    $editcheck=checkprivilege($menuprivilegearray, 48, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 48, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 48, 4);
}
else if($lastElement=='receipt.php'){
    $addcheck=checkprivilege($menuprivilegearray, 49, 1);
    $editcheck=checkprivilege($menuprivilegearray, 49, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 49, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 49, 4);
}
else if($lastElement=='accountcategory.php'){
    $addcheck=checkprivilege($menuprivilegearray, 50, 1);
    $editcheck=checkprivilege($menuprivilegearray, 50, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 50, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 50, 4);
}
else if($lastElement=='financialyearbranch.php'){
    $addcheck=checkprivilege($menuprivilegearray, 51, 1);
    $editcheck=checkprivilege($menuprivilegearray, 51, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 51, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 51, 4);
}
else if($lastElement=='dailypaymenttoaccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 52, 1);
    $editcheck=checkprivilege($menuprivilegearray, 52, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 52, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 52, 4);
}
else if($lastElement=='bank_reconcile.php'){
    $addcheck=checkprivilege($menuprivilegearray, 53, 1);
    $editcheck=checkprivilege($menuprivilegearray, 53, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 53, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 53, 4);
}
else if($lastElement=='deposit.php'){
    $addcheck=checkprivilege($menuprivilegearray, 54, 1);
    $editcheck=checkprivilege($menuprivilegearray, 54, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 54, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 54, 4);
}
else if($lastElement=='paymenttoaccount.php'){
    $addcheck=checkprivilege($menuprivilegearray, 55, 1);
    $editcheck=checkprivilege($menuprivilegearray, 55, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 55, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 55, 4);
}
else if($lastElement=='reverse_transaction.php'){
    $addcheck=checkprivilege($menuprivilegearray, 56, 1);
    $editcheck=checkprivilege($menuprivilegearray, 56, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 56, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 56, 4);
}
else if($lastElement=='rpt_ledger_folio.php'){
    $addcheck=checkprivilege($menuprivilegearray, 57, 1);
    $editcheck=checkprivilege($menuprivilegearray, 57, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 57, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 57, 4);
}
else if($lastElement=='rpt_trial_balance.php'){
    $addcheck=checkprivilege($menuprivilegearray, 58, 1);
    $editcheck=checkprivilege($menuprivilegearray, 58, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 58, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 58, 4);
}
else if($lastElement=='pettycash.php'){
    $addcheck=checkprivilege($menuprivilegearray, 59, 1);
    $editcheck=checkprivilege($menuprivilegearray, 59, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 59, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 59, 4);
}
else if($lastElement=='postpettycash.php'){
    $addcheck=checkprivilege($menuprivilegearray, 60, 1);
    $editcheck=checkprivilege($menuprivilegearray, 60, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 60, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 60, 4);
}
else if($lastElement=='rpt_balance_sheet.php'){
    $addcheck=checkprivilege($menuprivilegearray, 61, 1);
    $editcheck=checkprivilege($menuprivilegearray, 61, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 61, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 61, 4);
}
else if($lastElement=='rpt_period_trial_balance.php'){
    $addcheck=checkprivilege($menuprivilegearray, 62, 1);
    $editcheck=checkprivilege($menuprivilegearray, 62, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 62, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 62, 4);
}
else if($lastElement=='rpt_profit_and_loss.php'){
    $addcheck=checkprivilege($menuprivilegearray, 63, 1);
    $editcheck=checkprivilege($menuprivilegearray, 63, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 63, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 63, 4);
}
else if($lastElement=='account_balance_update.php'){
    $addcheck=checkprivilege($menuprivilegearray, 64, 1);
    $editcheck=checkprivilege($menuprivilegearray, 64, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 64, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 64, 4);
}
else if($lastElement=='pettycashreimbursement.php'){
    $addcheck=checkprivilege($menuprivilegearray, 65, 1);
    $editcheck=checkprivilege($menuprivilegearray, 65, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 65, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 65, 4);
}
else if($lastElement=='dayend.php'){
    $addcheck=checkprivilege($menuprivilegearray, 66, 1);
    $editcheck=checkprivilege($menuprivilegearray, 66, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 66, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 66, 4);
}

function checkprivilege($arraymenu, $menuID, $type){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID){
            if($type==1){
                return $array->add;
            }
            else if($type==2){
                return $array->edit;
            }
            else if($type==3){
                return $array->statuschange;
            }
            else if($type==4){
                return $array->remove;
            }
        }
    }
}
?>
<textarea class="d-none" id="actiontext"><?php echo $actionJSON; ?></textarea>
<input type="hidden" id="userType" value="<?php echo $_SESSION['type']; ?>">
<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2" href="dashboard.php">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>
            <?php if(menucheck($menuprivilegearray, 9)==1 | menucheck($menuprivilegearray, 8)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseproduct" aria-expanded="false" aria-controls="collapseproduct">
                <div class="nav-link-icon"><i data-feather="shopping-cart"></i></div>
                Product
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="product.php" | $lastElement=="productcategory.php"){echo 'show';} ?>" id="collapseproduct" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 9)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="product.php">Product</a>
                    <?php }if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="productcategory.php">Product Category</a>
                    <?php } ?>
                </nav>
            </div>
            <?php }if(menucheck($menuprivilegearray, 7)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="porder.php">
                <div class="nav-link-icon"><i data-feather="archive"></i></div>
                Purchsing Order
            </a>
            <?php }if(menucheck($menuprivilegearray, 6)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="grn.php">
                <div class="nav-link-icon"><i data-feather="truck"></i></div>
                Good Receive
            </a>
            <?php }if(menucheck($menuprivilegearray, 5)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="loading.php">
                <div class="nav-link-icon"><i class="fas fa-truck-loading"></i></div>
                Vehicle Loading
            </a>
            <?php } if(menucheck($menuprivilegearray, 14)==1 | menucheck($menuprivilegearray, 15)==1 | menucheck($menuprivilegearray, 16)==1 | menucheck($menuprivilegearray, 26)==1 | menucheck($menuprivilegearray, 27)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseorder" aria-expanded="false" aria-controls="collapseorder">
                <div class="nav-link-icon"><i data-feather="file"></i></div>
                Invoice
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="invoice.php" | $lastElement=="invoicepayment.php" | $lastElement=="paymentreceipt.php" | $lastElement=="invoiceview.php" | $lastElement=="invoicerecovery.php"){echo 'show';} ?>" id="collapseorder" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 14)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="invoice.php">Invoice Create</a>
                    <?php }if(menucheck($menuprivilegearray, 26)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="invoiceview.php">Invoice View</a>
                    <?php }if(menucheck($menuprivilegearray, 15)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="invoicepayment.php">Invoice Payment</a>
                    <?php }if(menucheck($menuprivilegearray, 27)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="invoicerecovery.php">Invoice Recovery</a>
                    <?php }if(menucheck($menuprivilegearray, 16)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="paymentreceipt.php">Payment Receipt</a>
                    <?php } ?>
                </nav>
            </div>
            <?php }if(menucheck($menuprivilegearray, 17)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="unloading.php">
                <div class="nav-link-icon"><i class="fas fa-warehouse"></i></div>
                Vehicle Unloading
            </a>
            <?php }if(menucheck($menuprivilegearray, 66)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="dayend.php">
                <div class="nav-link-icon"><i class="far fa-calendar-times"></i></div>
                Day End
            </a>
            <?php } if(menucheck($menuprivilegearray, 4)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="customer.php">
                <div class="nav-link-icon"><i data-feather="users"></i></div>
                Customer
            </a>
            <?php } if(menucheck($menuprivilegearray, 42)==1){ ?>
            <a class="nav-link p-0 px-3 py-2" href="damagereturn.php">
                <div class="nav-link-icon"><i data-feather="corner-down-left"></i></div>
                Damage Return
            </a>
            <?php } if(menucheck($menuprivilegearray, 23)==1 | menucheck($menuprivilegearray, 24)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsetarget" aria-expanded="false" aria-controls="collapsetarget">
                <div class="nav-link-icon"><i data-feather="target"></i></div>
                Target
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="employeetargetadd.php" | $lastElement=="vehicletargetadd.php"){echo 'show';} ?>" id="collapsetarget" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 24)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="vehicletargetadd.php">Vehicle Target</a>
                    <?php }if(menucheck($menuprivilegearray, 23)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="employeetargetadd.php">Employee Target</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 11)==1 | menucheck($menuprivilegearray, 12)==1 | menucheck($menuprivilegearray, 13)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseother" aria-expanded="false" aria-controls="collapseother">
                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                Other
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="vehicle.php" | $lastElement=="employee.php" | $lastElement=="area.php"){echo 'show';} ?>" id="collapseother" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 11)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="vehicle.php">Vehicle</a>
                    <?php }if(menucheck($menuprivilegearray, 12)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="employee.php">Employee</a>
                    <?php }if(menucheck($menuprivilegearray, 13)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="area.php">Area</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 18)==1 | menucheck($menuprivilegearray, 19)==1 | menucheck($menuprivilegearray, 20)==1 | menucheck($menuprivilegearray, 21)==1 | menucheck($menuprivilegearray, 22)==1 | menucheck($menuprivilegearray, 25)==1 | menucheck($menuprivilegearray, 38)==1 | menucheck($menuprivilegearray, 39)==1 | menucheck($menuprivilegearray, 40)==1 | menucheck($menuprivilegearray, 41)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsereport" aria-expanded="false" aria-controls="collapsereport">
                <div class="nav-link-icon"><i data-feather="file"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="stock.php" | $lastElement=="employeetarget.php" | $lastElement=="vehicletarget.php" | $lastElement=="customeroutstanding.php" | $lastElement=="dailysale.php" | $lastElement=="refsaleinfo.php" | $lastElement=="dailycash.php" | $lastElement=="salereportcustomer.php" | $lastElement=="salereportproduct.php" | $lastElement=="bufferstockmaintainreport.php"){echo 'show';} ?>" id="collapsereport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 18)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="stock.php">Stock</a>
                    <?php }if(menucheck($menuprivilegearray, 19)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="employeetarget.php">Employee Target</a>
                    <?php }if(menucheck($menuprivilegearray, 20)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="vehicletarget.php">Vehicle Target</a>
                    <?php }if(menucheck($menuprivilegearray, 21)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="customeroutstanding.php">Customer Outstanding</a>
                    <?php }if(menucheck($menuprivilegearray, 22)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="dailysale.php">Daily Sale</a>
                    <?php }if(menucheck($menuprivilegearray, 38)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="dailycash.php">Daily Cash & Cheque</a>
                    <?php }if(menucheck($menuprivilegearray, 25)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="refsaleinfo.php">Rep Sale Info</a>
                    <?php }if(menucheck($menuprivilegearray, 39)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="salereportcustomer.php">Sale Report Customer</a>
                    <?php }if(menucheck($menuprivilegearray, 40)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="salereportproduct.php">Sale Report Product</a>
                    <?php }if(menucheck($menuprivilegearray, 41)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="bufferstockmaintainreport.php">Buffer Maintainance</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 43)==1 | menucheck($menuprivilegearray, 44)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="false" aria-controls="collapseCompany">
                <div class="nav-link-icon"><i data-feather="home"></i></div>
                Company Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="company.php" | $lastElement=="companybranch.php"){echo 'show';} ?>" id="collapseCompany" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 43)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="company.php">Company</a>
                    <?php }if(menucheck($menuprivilegearray, 44)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="companybranch.php">Compay Branch</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 28)==1 | menucheck($menuprivilegearray, 29)==1 | menucheck($menuprivilegearray, 45)==1 | menucheck($menuprivilegearray, 47)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseBank" aria-expanded="false" aria-controls="collapseBank">
                <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                Bank
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="bank-info.php" | $lastElement=="branch-info.php" | $lastElement=="chequeinfo.php" | $lastElement=="paymenttype.php"){echo 'show';} ?>" id="collapseBank" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 28)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="bank-info.php">Bank</a>
                    <?php }if(menucheck($menuprivilegearray, 29)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="branch-info.php">Bank Branch</a>
                    <?php }if(menucheck($menuprivilegearray, 45)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="chequeinfo.php">Cheque Info</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 30)==1 | menucheck($menuprivilegearray, 31)==1 | menucheck($menuprivilegearray, 32)==1 | menucheck($menuprivilegearray, 33)==1 | menucheck($menuprivilegearray, 50)==1 | menucheck($menuprivilegearray, 34)==1 | menucheck($menuprivilegearray, 35)==1 | menucheck($menuprivilegearray, 36)==1 | menucheck($menuprivilegearray, 37)==1 | menucheck($menuprivilegearray, 48)==1 | menucheck($menuprivilegearray, 49)==1 | menucheck($menuprivilegearray, 51)==1 | menucheck($menuprivilegearray, 52)==1 | menucheck($menuprivilegearray, 53)==1 | menucheck($menuprivilegearray, 54)==1 | menucheck($menuprivilegearray, 55)==1 | menucheck($menuprivilegearray, 56)==1 | menucheck($menuprivilegearray, 57)==1 | menucheck($menuprivilegearray, 58)==1 | menucheck($menuprivilegearray, 59)==1 | menucheck($menuprivilegearray, 60)==1 | menucheck($menuprivilegearray, 61)==1 | menucheck($menuprivilegearray, 62)==1 | menucheck($menuprivilegearray, 63)==1 | menucheck($menuprivilegearray, 64)==1 | menucheck($menuprivilegearray, 65)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                <div class="nav-link-icon"><i data-feather="server"></i></div>
                Account Information
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="account-mainclass.php" | $lastElement=="account-subclass.php" | $lastElement=="account-mainaccount.php" | $lastElement=="account-subaccount.php"  | $lastElement=="accountcategory.php" | $lastElement=="financialyear.php" | $lastElement=="financialyearbranch.php" | $lastElement=="account-allocation.php" | $lastElement=="account-allocationtobankbranch.php" | $lastElement=="account-block.php" | $lastElement=="payment.php" | $lastElement=="receipt.php" | $lastElement=="dailypaymenttoaccount.php" | $lastElement=="bank_reconcile.php" | $lastElement=="deposit.php" | $lastElement=="paymenttoaccount.php" | $lastElement=="reverse_transaction.php" | $lastElement=="rpt_ledger_folio.php" | $lastElement=="rpt_trial_balance.php" | $lastElement=="pettycash.php" | $lastElement=="postpettycash.php" | $lastElement=="rpt_period_trial_balance.php" | $lastElement=="rpt_balance_sheet.php" | $lastElement=="rpt_profit_and_loss.php"  | $lastElement=="account_balance_update.php" | $lastElement=="pettycashreimbursement.php"){echo 'show';} ?>" id="collapseAccount" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <!-- Transactions -->
                    <a class="nav-link p-0 px-3 py-2 collapsed" href="#" data-toggle="collapse" data-target="#accordionAccountPayment" aria-expanded="false" aria-controls="accordionAccountPayment">
                    Transactions
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                    <div class="collapse <?php if($lastElement=="payment.php" | $lastElement=="receipt.php" | $lastElement=="dailypaymenttoaccount.php" | $lastElement=="bank_reconcile.php" | $lastElement=="deposit.php" | $lastElement=="paymenttoaccount.php" | $lastElement=="reverse_transaction.php"){echo 'show';} ?>" id="accordionAccountPayment" data-parent="#accordionAccountPayment">
                        <nav class="sidenav-menu-nested nav">
                            <?php if(menucheck($menuprivilegearray, 48)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="payment.php">Payment</a>
                            <?php }if(menucheck($menuprivilegearray, 49)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="receipt.php">Receipt</a>
                            <?php }if(menucheck($menuprivilegearray, 52)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="dailypaymenttoaccount.php">Receipt To Account</a>
                            <?php }if(menucheck($menuprivilegearray, 55)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="paymenttoaccount.php">Payment To Account</a>
                            <?php }if(menucheck($menuprivilegearray, 53)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="bank_reconcile.php">Bank Reconciliation</a>
                            <?php }if(menucheck($menuprivilegearray, 54)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="deposit.php">Deposit</a>
                            <?php }if(menucheck($menuprivilegearray, 56)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="reverse_transaction.php">Reverse Transaction</a>
                            <?php } ?>
                        </nav>
                    </div>
                    <!-- Petty Cash -->
                    <a class="nav-link p-0 px-3 py-2 collapsed" href="#" data-toggle="collapse" data-target="#accordionAccountPetty" aria-expanded="false" aria-controls="accordionAccountPetty">
                    Petty Cash
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                    <div class="collapse <?php if($lastElement=="pettycash.php" | $lastElement=="postpettycash.php" | $lastElement=="pettycashreimbursement.php"){echo 'show';} ?>" id="accordionAccountPetty" data-parent="#accordionAccountPetty">
                        <nav class="sidenav-menu-nested nav">
                            <?php if(menucheck($menuprivilegearray, 57)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="pettycash.php">Petty Cash Expense</a>
                            <?php }if(menucheck($menuprivilegearray, 58)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="postpettycash.php">Post Petty Cash</a>
                            <?php }if(menucheck($menuprivilegearray, 65)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="pettycashreimbursement.php">Petty Cash Reimbursement</a>
                            <?php } ?>
                        </nav>
                    </div>
                    <!-- Master -->
                    <a class="nav-link p-0 px-3 py-2 collapsed" href="#" data-toggle="collapse" data-target="#accordionSidenavLayout" aria-expanded="false" aria-controls="accordionSidenavLayout">
                    Master
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                    <div class="collapse <?php if($lastElement=="account-mainclass.php" | $lastElement=="account-subclass.php" | $lastElement=="account-mainaccount.php" | $lastElement=="account-subaccount.php"  | $lastElement=="accountcategory.php"  | $lastElement=="account_balance_update.php"){echo 'show';} ?>" id="accordionSidenavLayout" data-parent="#accordionSidenavLayout">
                        <nav class="sidenav-menu-nested nav">
                            <?php if(menucheck($menuprivilegearray, 30)==1){ ?> 
                            <a class="nav-link p-0 px-3 py-1" href="account-mainclass.php">Main Class</a>
                            <?php }if(menucheck($menuprivilegearray, 31)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-subclass.php">Sub Class</a>
                            <?php }if(menucheck($menuprivilegearray, 32)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-mainaccount.php">Main Account</a>
                            <?php }if(menucheck($menuprivilegearray, 33)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-subaccount.php">Sub Account</a>
                            <?php }if(menucheck($menuprivilegearray, 64)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account_balance_update.php">Account Balance</a>
                            <?php }if(menucheck($menuprivilegearray, 50)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="accountcategory.php">Account Category</a>
                            <?php } ?>
                        </nav>
                    </div>
                    <!-- Other -->
                    <a class="nav-link p-0 px-3 py-2 collapsed" href="#" data-toggle="collapse" data-target="#accordionAccountOther" aria-expanded="false" aria-controls="accordionAccountOther">
                    Other
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                    <div class="collapse <?php if($lastElement=="financialyear.php" | $lastElement=="financialyearbranch.php" | $lastElement=="account-allocation.php" | $lastElement=="account-allocationtobankbranch.php" | $lastElement=="account-block.php"){echo 'show';} ?>" id="accordionAccountOther" data-parent="#accordionAccountOther">
                        <nav class="sidenav-menu-nested nav">
                            <?php if(menucheck($menuprivilegearray, 34)==1){ ?> 
                            <a class="nav-link p-0 px-3 py-1" href="financialyear.php">Financial Year</a>
                            <?php }if(menucheck($menuprivilegearray, 51)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="financialyearbranch.php">Financial Year Allocation</a>
                            <?php }if(menucheck($menuprivilegearray, 35)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-allocation.php">Account Allocation</a>
                            <?php }if(menucheck($menuprivilegearray, 36)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-allocationtobankbranch.php">Account To Branch</a>
                            <?php }if(menucheck($menuprivilegearray, 37)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="account-block.php">Account Block</a>
                            <?php } ?>
                        </nav>
                    </div>
                    <!-- Reports -->
                    <a class="nav-link p-0 px-3 py-2 collapsed" href="#" data-toggle="collapse" data-target="#accordionAccountReports" aria-expanded="false" aria-controls="accordionAccountReports">
                    Reports
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                    <div class="collapse <?php if($lastElement=="rpt_ledger_folio.php" | $lastElement=="rpt_trial_balance.php" | $lastElement=="rpt_period_trial_balance.php" | $lastElement=="rpt_balance_sheet.php" | $lastElement=="rpt_profit_and_loss.php"){echo 'show';} ?>" id="accordionAccountReports" data-parent="#accordionAccountReports">
                        <nav class="sidenav-menu-nested nav">
                            <?php if(menucheck($menuprivilegearray, 57)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="rpt_ledger_folio.php">Ledger Folio</a>
                            <?php }if(menucheck($menuprivilegearray, 58)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="rpt_trial_balance.php">Trial Balance</a>
                            <?php }if(menucheck($menuprivilegearray, 61)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="rpt_period_trial_balance.php">Period Trial Balance</a>
                            <?php }if(menucheck($menuprivilegearray, 62)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="rpt_balance_sheet.php">Balance Sheet</a>
                            <?php }if(menucheck($menuprivilegearray, 63)==1){ ?>
                            <a class="nav-link p-0 px-3 py-1" href="rpt_profit_and_loss.php">P & L</a>
                            <?php } ?>
                        </nav>
                    </div>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i data-feather="user"></i></div>
                User Account
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($lastElement=="useraccount.php" | $lastElement=="usertype.php" | $lastElement=="userprivilege.php"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 1)==1){ ?> 
                    <a class="nav-link p-0 px-3 py-1" href="useraccount.php">User Account</a>
                    <?php }if(menucheck($menuprivilegearray, 2)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="usertype.php">Type</a>
                    <?php }if(menucheck($menuprivilegearray, 3)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="userprivilege.php">Privilege</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="sidenav-footer bg-laugfs">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>
