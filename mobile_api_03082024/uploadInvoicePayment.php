<?php
require_once('dbConnect.php');

$userID = $_POST['refId'];
$tblData = json_decode($_POST['invoicePaymentArray']);
$tblPayData = json_decode($_POST['customerPaymentArray']);
$totAmount = $_POST['totAmount'];
$payAmount = $_POST['payAmount'];
$balAmount = $_POST['balAmount'];
$claimStatus=0;
$hiddencustomerID=0;
$hidePendingAmount=$_POST['hidePendingAmount'];

$today = date('Y-m-d');
$updatedatetime = date('Y-m-d h:i:s');
$flag = true;
$con->autocommit(FALSE);

$insertpayment = "INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$payAmount','$balAmount','1','$updatedatetime','$userID')";

if($claimStatus==1){
    $updateexcesspayment="UPDATE `tbl_invoice_excess_payment` SET `paydate`='$today',`paystatus`='1' WHERE `tbl_customer_idtbl_customer`='$hiddencustomerID'";
    $con->query($updateexcesspayment);
}

if ($con->query($insertpayment)) {
    $paymentID = $con->insert_id;

    if($hidePendingAmount<$payAmount){
        $insertexcesspayment="INSERT INTO `tbl_invoice_excess_payment`(`date`, `excess_amount`, `paydate`, `paystatus`, `status`, `updatedatetime`, `updateuser`, `tbl_invoice_payment_idtbl_invoice_payment`, `tbl_customer_idtbl_customer`) VALUES ('$today','$balAmount','','','1','$updatedatetime','$userID','$paymentID','$hiddencustomerID')";
        $con->query($insertexcesspayment);
    }

    foreach ($tblData as $rowtabledata) {
        $invno = $rowtabledata->invoiceno;
        $invamount = $rowtabledata->invamount;
        $invpayamount = $rowtabledata->invpayamount;

        if ($invamount <= $invpayamount) {
            $paymentcompletestatus = 1;
            $fullstatus = 1;
            $halfstatus = 0;
        } else {
            $paymentcompletestatus = 0;
            $fullstatus = 0;
            $halfstatus = 1;
        }

        if ($invpayamount > 0) {
            $updateinvoice = "UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcompletestatus',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invno'";
            if (!$con->query($updateinvoice)) {
                $flag = false;
            }

            $updateinvoicehas = "INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`, `payamount`, `fullstatus`, `halfstatus`) VALUES ('$paymentID','$invno','$invpayamount','$fullstatus','$halfstatus')";
            if (!$con->query($updateinvoicehas)) {
                $flag = false;
            }
        }
    }
    
    foreach ($tblPayData as $rowtablepaydata) {
        $typename = $rowtablepaydata->typename;
        $cashamount = $rowtablepaydata->cashamount;
        $bankamount = $rowtablepaydata->bankamount;
        $chequeno = $rowtablepaydata->chequeno;
        $receiptno = $rowtablepaydata->receiptno;
        $chequedate = $rowtablepaydata->chequedate;
        $bankID = $rowtablepaydata->bankID;
        $branch = '-';
        $typeID = "";

        if ($typename == "Cash") {
            $typeID = "1";
            $paidamount=$cashamount;
        } else {
            $typeID = "2";
            $paidamount=$bankamount;
        }

        $insertpaydetail ="INSERT INTO `tbl_invoice_payment_detail`(`method`, `amount`, `branch`, `receiptno`, `chequeno`, `chequedate`, `status`, `updatedatetime`, `tbl_bank_idtbl_bank`, `tbl_user_idtbl_user`, `tbl_invoice_payment_idtbl_invoice_payment`) VALUES ('$typeID','$paidamount','$branch','$receiptno','$chequeno','$chequedate','1','$updatedatetime','$bankID','$userID','$paymentID')";
        if (!$con->query($insertpaydetail)) {
            $flag = false;
        }
    }
} else {
    $flag = false;
}

if ($flag) {
    $con->commit();
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $con->rollback();
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}
?>