<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_vehicle` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank =$conn-> query($sqlbank); 

$sqlbank2="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank2 =$conn-> query($sqlbank2); 

include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Invoice Payment</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select class="form-control form-control-sm" name="customer" id="customer" required>
                                            <option value="">Select</option>
                                            <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name']; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Invoice Search</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" placeholder="" aria-label="Invoice Number" aria-describedby="button-addon2" id="formInvNum">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-dark" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div id="invoiceviewdetail"></div>
                                <hr class="border-dark">
                                <button type="button" class="btn btn-outline-danger btn-sm fa-pull-right" disabled id="invPaymentCreateBtn"><i class="fas fa-receipt"></i>&nbsp;Issue Payment Receipt</button>
                                <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-2" id="invPaymentCheckBtn" disabled><i class="fas fa-tasks"></i>&nbsp;Check Payment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Alert -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body text-white">
                <div class="row">
                    <div class="col" id="bodyAlert"></div>
                </div>
                <button type="button" class="btn btn-outline-light btn-sm fa-pull-right pl-4 pr-4" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!--Issue Payment Receipt Modal-->
<div class="modal fade" id="paymentmodal" tabindex="-1" role="dialog" aria-labelledby="oLevel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header p-0 p-2">
                <h5 class="modal-title" id="oLevelTitle">Issue Payment Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <form id="formModal">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <input type="date" id="date" name="date" class="form-control form-control-sm"
                                    value="<?php echo date('Y-m-d') ?>" required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Payment Amount</label>
                                <input id="paymentPayAmount" name="paymentPayAmount" type="text" class="form-control form-control-sm" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Excess Amount</label>
                                
                                <div class="input-group mb-3">
                                    <input id="excess_amount" name="excess_amount" type="text" class="form-control form-control-sm" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" id="claimpayment" name="claimpayment" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="paymentMethod1" name="paymentMethod"
                                            class="custom-control-input" value="1" data-toggle="collapse"
                                            href="#collapseOne">
                                        <label class="custom-control-label" for="paymentMethod1">Cash</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="paymentMethod2" name="paymentMethod"
                                            class="custom-control-input" value="2" data-toggle="collapse"
                                            href="#collapseTwo">
                                        <label class="custom-control-label" for="paymentMethod2">Bank / Cheque</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="paymentMethod3" name="paymentMethod"
                                            class="custom-control-input" value="3" data-toggle="collapse"
                                            href="#collapseThree">
                                        <label class="custom-control-label" for="paymentMethod3">Online</label>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <div class="card shadow-none border-0">
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cash Advance</label>
                                                <input id="paymentCash" name="paymentCash" type="text" class="form-control form-control-sm" placeholder="" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card shadow-none border-0">
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque / Deposit Advance</label>
                                                <input id="paymentCheque" name="paymentCheque" type="text" class="form-control form-control-sm" placeholder="" required readonly>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque Number</label>
                                                <input id="paymentChequeNum" name="paymentChequeNum" type="text" class="form-control form-control-sm" placeholder="" readonly>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Receipt Number</label>
                                                <input id="paymentReceiptNum" name="paymentReceiptNum" type="text" class="form-control form-control-sm" placeholder="" readonly>
                                                <small id="" class="form-text text-muted">Bank deposit receipt number only</small>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque Date</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="date" class="form-control dpd1a" placeholder="" name="paymentchequeDate" id="paymentchequeDate" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Bank Name</label>
                                                <select id="paymentBank" name="paymentBank" class="form-control form-control-sm" disabled>
                                                    <option value="">Select</option>
                                                    <?php if($resultbank->num_rows > 0) {while ($rowbank = $resultbank-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card shadow-none border-0">
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-0">
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Deposit Advance</label>
                                                <input id="paymentCheque2" name="paymentCheque2" type="text" class="form-control form-control-sm" placeholder="" required readonly>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Refference Number</label>
                                                <input id="refferenceNum" name="refferenceNum" type="text" class="form-control form-control-sm" placeholder="" readonly>
                                                <small id="" class="form-text text-muted">Online Transfer only</small>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Cheque Date</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="date" class="form-control dpd1a" placeholder="" name="paymentchequeDate2" id="paymentchequeDate2" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group mb-1">
                                                <label class="small font-weight-bold text-dark">Bank Name</label>
                                                <select id="paymentBank2" name="paymentBank2" class="form-control form-control-sm" disabled>
                                                    <option value="">Select</option>
                                                    <?php if($resultbank2->num_rows > 0) {while ($rowbank = $resultbank2-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button name="submitBtnModal" type="button" id="submitBtnModal" class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-file-invoice-dollar"></i>&nbsp;Add Payment</button>
                                <input type="submit" class="d-none" id="hideSubmitModal">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <table class="table table-bordered table-sm table-striped" id="tblPaymentTypeModal">
                            <thead>
                                <th>Type</th>
                                <th class="text-right">Cash</th>
                                <th class="text-right">Cheque / Deposit</th>
                                <th>Che No</th>
                                <th>Receipt</th>
                                <th>Che Date</th>
                                <th>Bank</th>
                                <th class="d-none">BankID</th>
                                <th class="d-none">paymethod</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Total Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="totAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Pay Amount :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="payAmount"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">&nbsp;</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <hr class="border-dark">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 text-right">Balance :</div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-right">
                                <div id="balanceAmount"></div>
                            </div>
                        </div>
                        <input type="hidden" id="hidePayAmount" value="0">
                        <input type="hidden" id="hideBalAmount" value="0">
                        <input type="hidden" id="hideAllBalAmount" value="0">
                        <input type="hidden" id="hidependingamount" value="0">
                        <input type="hidden" id="hiddencustomerID">


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
                        <button class="btn btn-outline-danger btn-sm" id="btnIssueInv" disabled><i class="fas fa-file-pdf"></i>&nbsp;Issue Payment Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Receipt -->
<div class="modal fade" id="modalpaymentreceipt" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewreceiptprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnreceiptprint"><i class="fas fa-print"></i>&nbsp;Print Receipt</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-danger">
      <div class="modal-body">
        <p class="text-light font-weight-bold">The entered value exceeds the balance amount.</p>
      </div>
    </div>
  </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        // Filtor part start
        $("#customer").select2({
            ajax: {
                url: 'getprocess/getcustomerlist.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term 
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#customer').change(function(){
            var customerID = $(this).val();
            if(customerID!=''){
                $('#formInvNum').val('');
                $('#invoiceviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        customerID: customerID
                    },
                    url: 'getprocess/getinvoicepayment.php',
                    success: function(result) {//alert(result);
                        $('#invoiceviewdetail').html(result);
                        $('#invPaymentCheckBtn').prop("disabled", false);
                        tblcheckboxevent();
                    }
                });  
            }
        });
        $('#formSearchBtn').click(function(){
            $('#customer').val(null).trigger('change');
            $('#invoiceviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

            var invoiceno = $('#formInvNum').val();
            if(invoiceno!=''){
                $('#formInvNum').removeClass('bg-danger-soft');
                $.ajax({
                    type: "POST",
                    data: {
                        invoiceno: invoiceno
                    },
                    url: 'getprocess/getinvoicepayment.php',
                    success: function(result) {//alert(result);
                        $('#invoiceviewdetail').html(result);
                        $('#invPaymentCheckBtn').prop("disabled", false);
                        tblcheckboxevent();
                    }
                });
            }
            else{
                $('#formInvNum').addClass('bg-danger-soft');
            }
        });
        // Filtor part end

        // Payment create start
        $('#invPaymentCheckBtn').click(function() {
            var promise = tblTextRemove();

            promise.then(function() {
                var value = 0;

                var table = $("#paymentDetailTable tbody");
                table.find('tr').each(function(i, el) {
                    var row = $(this);
                    var tds = $(this).find('td');
                    value += parseFloat(tds.eq(8).text());
                });

                if (value == '0.00') {
                    $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value or uncheck full payment | halfpayment check box.</p>');
                    $('#alertModal').modal({
                        keyboard: false,
                        backdrop: 'static'
                    });
                    $('#invPaymentCreateBtn').prop("disabled", true);
                } else {
                    $('#invPaymentCreateBtn').prop("disabled", false);
                }
            });

        });
        $('#invPaymentCreateBtn').click(function() {
            var table = $("#paymentDetailTable tbody");
            var total = 0;
            var bal = 0;
            var customerID = ''; // Declare customerID outside the loop
            var invnetBal = 0;
            var invBal = 0;
            
            table.find('tr').each(function(i, el) {
                var $tds = $(this).find('td');
                var value = parseFloat($tds.eq(10).text());
                customerID = $tds.eq(11).text(); // Assign value directly to customerID
                
                total += parseFloat(value);
                
                var balText = $tds.eq(7).text();
                if ($tds.find('.fullAmount').is(':checked') || $tds.find('.halfAmount').is(':checked')) {
                    bal += parseFloat(balText.replace(/,/g, ''));
                }
                
                invBal += parseFloat(invBal);
            });

            balance = parseFloat(bal).toFixed(2);
            total = parseFloat(total).toFixed(2);
            invBal = parseFloat(invBal).toFixed(2);
            invnetBal = parseFloat(total + invBal).toFixed(2);

            if (invnetBal == '0.00') {
                $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value and press the create payment button</p>');
                $('#alertModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                $('#invPaymentCreateBtn').prop("disabled", true);
            } else {
                $('#paymentPayAmount').val(invnetBal);
                $('#hiddencustomerID').val(customerID);
                $('#totAmount').html(invnetBal);
                $('#hideAllBalAmount').val(invnetBal);
                $('#hidependingamount').val(balance);
                $('#paymentmodal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
            }

            $.ajax({
                type: "POST",
                data: {
                    recordID: customerID
                },
                dataType: "json",
                url: 'getprocess/getexcesspaymentforcustomer.php',
                success: function(response) {
                    if (response.amount === null || response.amount === "") {
                        $('#excess_amount').val("0.00");
                    } else {
                        $('#excess_amount').val(response.amount);
                    }
                }
            });
        });
        $('#claimpayment').change(function() {
            if(this.checked) {
                var paymentPayAmount = parseFloat($('#paymentPayAmount').val());
                var excess_amount = parseFloat($('#excess_amount').val());

                if(paymentPayAmount<excess_amount){
                    $('#submitBtnModal').prop('disabled', true);

                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Claim By Excess Payment</td><td class="text-right">' + parseFloat(excess_amount).toFixed(2) + '</td><td class="">-</td><td class="">-</td><td>-</td><td>-</td><td>-</td><td class="d-none">0</td><td class="d-none">4</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(excess_amount);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#btnIssueInv').prop('disabled', false);
                }else{
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Claim By Excess Payment</td><td class="text-right">' + parseFloat(excess_amount).toFixed(2) + '</td><td class="">-</td><td class="">-</td><td>-</td><td>-</td><td>-</td><td class="d-none">0</td><td class="d-none">4</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(excess_amount);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);
                    
                    $('#paymentCash').val((balance).toFixed(2));
                    $('#paymentCheque').val((balance).toFixed(2));
                    $('#paymentCheque2').val((balance).toFixed(2));

                    $('#btnIssueInv').prop('disabled', false);
                }
            }
            else{
                var paymentPayAmount = parseFloat($('#paymentPayAmount').val());

                $("#tblPaymentTypeModal > tbody").empty();
                $('#hideBalAmount').val('0');
                $('#balanceAmount').html('');
                $('#payAmount').html('');
                $('#hidePayAmount').val('0');

                $('#paymentCash').val((paymentPayAmount).toFixed(2));
                $('#paymentCheque').val((paymentPayAmount).toFixed(2));
                $('#paymentCheque2').val((paymentPayAmount).toFixed(2));

                $('#btnIssueInv').prop('disabled', true);
                $('#submitBtnModal').prop('disabled', false);
            }
        });
        $('input[type=radio][name=paymentMethod]').change(function() {
            if (this.value == '1') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentReceiptNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("disabled", true);
                $('#paymentCash').prop("readonly", false);
            } else if (this.value == '2') {
                $('#paymentCheque').prop("readonly", false);
                $('#paymentChequeNum').prop("readonly", false);
                $('#paymentReceiptNum').prop("readonly", false);
                $('#paymentchequeDate').prop("readonly", false);
                $('#paymentBank').prop("disabled", false);
                $('#paymentCash').prop("readonly", true);
            } else {
                $('#paymentCheque2').prop("readonly", false);
                $('#refferenceNum').prop("readonly", false);
                $('#paymentchequeDate2').prop("readonly", false);
                $('#paymentBank2').prop("disabled", false);
                $('#paymentCash').prop("readonly", true);
            }
        });
        $("#submitBtnModal").click(function() {
            if (!$("#formModal")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideSubmitModal").click();
            } else {
                var paymenttype = $('input[type=radio][name=paymentMethod]:checked').val();
                var paymentCheque = $('#paymentCheque').val();
                var paymentCheque2 = $('#paymentCheque2').val();
                var paymentChequeNum = $('#paymentChequeNum').val();
                var paymentReceiptNum = $('#paymentReceiptNum').val();
                var paymentchequeDate = $('#paymentchequeDate').val();
                var paymentchequeDate2 = $('#paymentchequeDate2').val();
                var paymentBankID = $('#paymentBank').val();
                var paymentBankID2 = $('#paymentBank2').val();
                var paymentBank = $("#paymentBank option:selected").text();
                var paymentBank2 = $("#paymentBank2 option:selected").text();
                var paymentCash = $('#paymentCash').val();
                var refferenceNum = $('#refferenceNum').val();

                if(paymenttype==1){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Cash</td><td class="text-right">' + parseFloat(paymentCash).toFixed(2) + '</td><td class="">-</td><td class="">-</td><td>-</td><td>-</td><td>-</td><td class="d-none">0</td><td class="d-none">1</td></tr>');
                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(paymentCash);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#paymentCash').val('').prop('readonly', true);
                    $('#paymentMethod1').prop('checked', false);

                    $('#btnIssueInv').prop('disabled', false);
                }
                else if(paymenttype==2){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Bank / Cheque</td><td class="">-</td><td class="text-right">' + parseFloat(paymentCheque).toFixed(2) + '</td><td class="">'+paymentChequeNum+'</td><td>'+paymentReceiptNum+'</td><td>'+paymentchequeDate+'</td><td>'+paymentBank+'</td><td class="d-none">'+paymentBankID+'</td><td class="d-none">2</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(paymentCheque);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#paymentCheque').val('').prop('readonly', true);
                    $('#paymentChequeNum').val('').prop('readonly', true);
                    $('#paymentReceiptNum').val('').prop('readonly', true);
                    $('#paymentchequeDate').val('').prop('readonly', true);
                    $('#paymentBank').val('').prop('disabled', true);
                    $('#paymentMethod2').prop('checked', false);

                    $('#btnIssueInv').prop('disabled', false);
                } else{
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Bank / Cheque</td><td class="">-</td><td class="text-right">' + parseFloat(paymentCheque2).toFixed(2) + '</td><td class="">-</td><td>'+refferenceNum+'</td><td>'+paymentchequeDate2+'</td><td>'+paymentBank2+'</td><td class="d-none">'+paymentBankID2+'</td><td class="d-none">3</td></tr>');

                    var paidAmount = parseFloat($('#hidePayAmount').val());
                    var PayAmount = parseFloat(paymentCheque2);
                    var paymentPayAmount = parseFloat($('#hideAllBalAmount').val());

                    paidAmount = (paidAmount + PayAmount);
                    var balance = (paymentPayAmount - paidAmount);
                    $('#hideBalAmount').val(balance);
                    $('#balanceAmount').html((balance).toFixed(2));
                    $('#payAmount').html((paidAmount).toFixed(2));
                    $('#hidePayAmount').val(paidAmount);

                    $('#paymentCheque').val('').prop('readonly', true);
                    $('#paymentChequeNum').val('').prop('readonly', true);
                    $('#paymentReceiptNum').val('').prop('readonly', true);
                    $('#paymentchequeDate').val('').prop('readonly', true);
                    $('#paymentBank').val('').prop('disabled', true);
                    $('#paymentMethod2').prop('checked', false);

                    $('#btnIssueInv').prop('disabled', false);
                }
                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
            }
        });
        $('#btnIssueInv').click(function(){
            jsonObj = [];
            $("#paymentDetailTable tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });

            var myJsonString = JSON.stringify(jsonObj);
            // console.log(myJsonString);

            jsonObjOne = [];
            $("#tblPaymentTypeModal tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObjOne.push(item);
            });

            var myJsonString2 = JSON.stringify(jsonObjOne);
            // console.log(myJsonString2);

            var totAmount = $('#paymentPayAmount').val();
            var payAmount = $('#hidePayAmount').val();
            var balAmount = $('#hideBalAmount').val();
            var hidePendingAmount = $('#hidependingamount').val();
            var hiddencustomerID = $('#hiddencustomerID').val();
            if( $('#claimpayment').is(':checked') ){var claimStatus = $('#claimpayment').val();}
            else{var claimStatus = 0;}
            var date = $('#date').val();

            $.ajax({
                type: "POST",
                data: {
                    tblData: jsonObj,
                    tblPayData: jsonObjOne,
                    totAmount: totAmount,
                    payAmount: payAmount,
                    balAmount: balAmount,
                    hidePendingAmount: hidePendingAmount,
                    hiddencustomerID: hiddencustomerID,
                    claimStatus: claimStatus,
                    date: date
                },
                url: 'process/invoicepaymentprocess.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    if(obj.paymentinvoice>0){
                        $('#paymentmodal').modal('hide');
                        paymentreceiptview(obj.paymentinvoice);
                        $('#paymentmodal').modal('hide');
                        $('#modalpaymentreceipt').modal('show');
                    }
                    action(obj.action);
                }
            });
        });
        document.getElementById('btnreceiptprint').addEventListener ("click", print);
        $('#modalpaymentreceipt').on('hidden.bs.modal', function (e) {
            location.reload();
        });
        // Payment create end  
        $(document).on('keyup', '.editfieldpay', function () {
            var enteredValue = $(this).val().replace(/,/g, '');
            var column5Text = $(this).closest('tr').find('td:eq(7)').text();
            var column5Value = parseFloat(column5Text.replace(/,/g, ''));

            if (parseFloat(enteredValue) > column5Value) {
                $('#myModal').modal('show');
                $('#invPaymentCheckBtn').prop('disabled', true);
            } else {
                $('#invPaymentCheckBtn').prop('disabled', false);
            }
        });      
    });

    function tblcheckboxevent() {
        $('#paymentDetailTable tbody').on('click', '.fullAmount', function() {
            var row = $(this);
            if ((row.closest('.fullAmount')).is(':checked')) {
                var fullAmount = row.closest("tr").find('td:eq(5)').text();
                row.closest("tr").find('td:eq(10)').text(fullAmount);
            } else {
                row.closest("tr").find('td:eq(10)').text('0.00');
            }
        });

        $('#paymentDetailTable tbody').on('click', '.halfAmount', function() {
            var row = $(this);
            if ((row.closest('.halfAmount')).is(':checked')) {
                tblpayamount();
            } else {
                tblTextRemove();
                row.closest("tr").find('td:eq(10)').text('0.00');
            }
        });
    }

    function tblTextRemove() {
        $('#paymentDetailTable .editfield').each(function() {
            $this = $(this);
            var val = $this.val();
            var td = $this.closest('td');
            td.empty().html(parseFloat(val).toFixed(2)).data('editing', false);
        });

        var deferred = $.Deferred();

        setTimeout(function() {
            // completes status
            deferred.resolve();
        }, 1000);

        // returns complete status
        return deferred.promise();
    }

    function tblpayamount() {
        $('.paidAmount').click(function(e) {
            if (($(this).closest('tr')).find('td:eq(9) .halfAmount').is(':checked')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                $this = $(this);
                if ($this.data('editing')) return;

                var val = $this.text();

                $this.empty();
                $this.data('editing', true);

                $('<input type="text" class="form-control form-control-sm editfieldpay">').val(val).appendTo($this);
            }
        });
        putOldValueBack = function() {
            $('.editfieldpay').each(function() {
                $this = $(this);
                var val = $this.val();
                var td = $this.closest('td');
                td.empty().html(parseFloat(val).toFixed(2)).data('editing', false);
            });
        }
        $(document).click(function(e) {
            putOldValueBack();
        });
    }

    function paymentreceiptview(paymentinoiceID){
        $('#viewreceiptprint').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

        $.ajax({
            type: "POST",
            data: {
                paymentinoiceID: paymentinoiceID
            },
            url: 'getprocess/getpaymentreceipt.php',
            success: function(result) { //alert(result);
                $('#viewreceiptprint').html(result);
            }
        });
    }

    function print() {
        printJS({
            printable: 'viewreceiptprint',
            type: 'html',
            targetStyles: ['*']
        })
    }

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }

</script>
<?php include "include/footer.php"; ?>
