<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_product_category` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlreflist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7";
$resultreflist =$conn-> query($sqlreflist);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct);

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank =$conn-> query($sqlbank); 

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
                            <span>Invoice Create</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="invcreateform" autocomplete="off">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" id="invoicedate" name="invoicedate" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" required>
                                            </div>
                                        </div> 
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Executive Name*</label>
                                            <select class="form-control form-control-sm" name="ref" id="ref" required>
                                                <option value="">Select</option>
                                                <?php if($resultreflist->num_rows > 0) {while ($rowreflist = $resultreflist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowreflist['idtbl_employee'] ?>"><?php echo $rowreflist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div> 
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Load list*</label>
                                            <select class="form-control form-control-sm" name="vehicleload" id="vehicleload" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Area*</label>
                                            <select class="form-control form-control-sm" name="area" id="area" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select class="form-control form-control-sm" name="customer" id="customer" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Customer Available Stock -->
                                    <div class="collapse" id="customeravastock">
                                        <div class="card card-body p-0 border-0 shadow-none">
                                            <div class="form-row mt-3">
                                                <div class="col">
                                                    <h6 class="small title-style font-weight-bold"><span>Customer Qty</span></h6>
                                                </div>
                                            </div>
                                            <div id="customeravaqtydiv"></div>
                                        </div>
                                    </div>
                                    <!-- Customer Available Stock -->
                                    <!-- Invoice Information -->
                                    <!-- <div class="collapse" id="invoiceinfo">
                                        <div class="card card-body p-0 border-0 shadow-none">
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Product*</label>
                                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                                        <option value="">Select</option>
                                                        <?php //if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                                        <option value="<?php //echo $rowproduct['idtbl_product'] ?>"><?php //echo $rowproduct['product_name'] ?></option>
                                                        <?php //}} ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Available Qty</label>
                                                    <input type="text" name="avaqty" id="avaqty" class="form-control form-control-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">New</label>
                                                    <input type="text" name="newqty" id="newqty" class="form-control form-control-sm" value="0">
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Refill</label>
                                                    <input type="text" name="refillqty" id="refillqty" class="form-control form-control-sm" value="0">
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Empty</label>
                                                    <input type="text" name="emptyqty" id="emptyqty" class="form-control form-control-sm" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <button type="button" id="submitbtn" class="btn btn-outline-primary btn-sm px-5 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                                <input name="hidesubmit" type="submit" value="Save" id="hidesubmit" class="d-none">
                                                <input type="hidden" name="newprice" id="newprice" value="">
                                                <input type="hidden" name="refillprice" id="refillprice" value="">
                                                <input type="hidden" name="emptyprice" id="emptyprice" value="">
                                                <input type="hidden" name="discountedprice" id="discountedprice" value="">
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- Invoice Information -->
                                </form>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-10">
                                <h6 class="small title-style font-weight-bold my-2"><span>Invoice Detail</span></h6>
                                <div class="scrollbar pb-3" id="style-2">
                                <table class="table table-striped table-bordered table-sm small" id="tableinvoice">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">Product</th>
                                            <th class="d-none" style="width: 100px;">ProductID</th>
                                            <th class="d-none" style="width: 100px;">Unitprice</th>
                                            <th class="d-none" style="width: 100px;">Refillprice</th>
                                            <th class="d-none" style="width: 100px;">Emptyprice</th>
                                            <th class="d-none" style="width: 40px;">Encustomer New Price</th>
                                            <th class="d-none" style="width: 40px;">Encustomer Refill Price</th>
                                            <th class="d-none" style="width: 40px;">Encustomer Empty Price</th>
                                            <th class="d-none" style="width: 100px;">NewSale Price with VAT</th>
                                            <th class="d-none" style="width: 100px;">RefillSale Price with VAT</th>
                                            <th class="d-none" style="width: 100px;">EmptySale Price with VAT</th>
                                            <th class="d-none" style="width: 100px;">Encustomer New Price with VAT</th>
                                            <th class="d-none" style="width: 100px;">Encustomer refill Price with VAT</th>
                                            <th class="d-none" style="width: 100px;">Encustomer Empty Price with VAT</th>
                                            <th class="text-center" style="width: 40px;">New Price</th>
                                            <th class="text-center" style="width: 40px;">Refill Price</th>
                                            <th class="text-center" style="width: 40px;">Empty Price</th>
                                            <th class="text-center" style="width: 40px;">Encustomer New Price</th>
                                            <th class="text-center" style="width: 40px;">Encustomer Refill Price</th>
                                            <th class="text-center" style="width: 40px;">Encustomer Empty Price</th>
                                            <th class="text-center" style="width: 40px;">Available</th>
                                            <th class="text-center" style="width: 45px;">Empty</th>
                                            <th class="text-center" style="width: 50px;">New</th>
                                            <th class="text-center" style="width: 50px;">Refill</th>
                                            <th class="text-center" style="width: 50px;">Empty</th>
                                            <th class="text-center" style="width: 50px;">Trust</th>
                                            <th class="text-center" style="width: 50px;">Trust Return</th>
                                            <th class="d-none" style="width: 100px;">HideTotal</th>
                                            <th class="text-center" style="width: 50px;">VAT</th>
                                            <th class="text-right" style="width: 80px;">Total</th>
                                            <th class="d-none" style="width: 100px;">HideTotalWithoutVAT</th>
                                            <th class="d-none" style="width: 100px;">Discountprice</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                                </div>
                                <div class="row">
                                    <div class="col text-right"><h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1></div>
                                    <input type="hidden" id="hidetotalinvoice" value="0">
                                    <input type="hidden" id="hidetotalinvoicewithoutvat" value="0">
                                </div>
                                <div class="row">
                                    <div class="col-6">&nbsp;</div>
                                    <div class="col-6">
                                        <button class="btn btn-outline-primary btn-sm fa-pull-right mt-2 px-4" id="btnissuemodal">Issue Invoice</button>
                                    </div>
                                </div>
                                <div class="form-group mt-3 text-danger small">
                                    <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                                </div>
                                <div class="row">
                                    <div class="col-12 small text-primary text-justify">
                                        <hr>Now you can add the invoice payment click the invoice number & add payment. Payment complete invoice show "Green" colour.</div>
                                </div>
                            </div>
                            <div class="col-2">
                                <h6 class="small title-style font-weight-bold my-2"><span>Issue Invoices</span></h6>
                                <ul class="list-group mt-2" id="invoicelist"></ul>
                                <!-- <button class="btn btn-outline-dark btn-sm fa-pull-right mt-2" id="btncompleteinvoice" disabled>Complete Invoices</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Warning -->
<div class="modal fade" style="z-index: 2000; " id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Invoice -->
<div class="modal fade" id="modalinvoicepayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="staticBackdropLabel">Invoice Payment</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="invoiceviewdetail"></div>
                <hr class="border-dark">
                <button type="button" class="btn btn-outline-danger btn-sm fa-pull-right" disabled id="invPaymentCreateBtn"><i class="fas fa-receipt"></i>&nbsp;Issue Payment Receipt</button>
                <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-2" id="invPaymentCheckBtn" disabled><i class="fas fa-tasks"></i>&nbsp;Check Payment</button>
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
                                <input id="excess_amount" name="excess_amount" type="text" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="form-group mb-1">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" id="claimpayment" name="claimpayment"
                                        class="custom-control-input" value="1">
                                    <label class="custom-control-label text-danger font-weight-bold" for="claimpayment">Claim Excess Payment</label>
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
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="paymentchequeDate" id="paymentchequeDate" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    </div>
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
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="paymentchequeDate2" id="paymentchequeDate2" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    </div>
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
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="viewmessage"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <a href="dayend.php" class="btn btn-outline-light btn-sm">Go To Day End</a>
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
    var paymentMethodSelected = false;
    var claimPaymentChecked = false;

    function updateTotalAmount() {
        var totalAmount;
        var paymentMethodValue = document.querySelector('input[name="paymentMethod"]:checked').value;
        
        if (paymentMethodValue === "1") {
            totalAmount = parseFloat(document.getElementById('paymentCash').value);
        } else if (paymentMethodValue === "2") {
            totalAmount = parseFloat(document.getElementById('paymentCheque').value);
        } else if (paymentMethodValue === "3") {
            totalAmount = parseFloat(document.getElementById('paymentCheque2').value);
        }
        
        var formattedTotalAmount = isNaN(totalAmount) ? "0.00" : totalAmount.toFixed(2);
        
        document.getElementById('totAmount').innerText = formattedTotalAmount;
        document.getElementById('hideAllBalAmount').value = formattedTotalAmount;

    }

    function updatePaymentAmounts() {
        var excessAmountInput = document.getElementById('excess_amount');
        var excessAmount = parseFloat(excessAmountInput.value) || 0;
        var paymentCashInput = document.getElementById('paymentCash');
        var paymentCash = parseFloat(paymentCashInput.value) || 0;
        var paymentChequeInput = document.getElementById('paymentCheque');
        var paymentCheque = parseFloat(paymentChequeInput.value) || 0;
        var paymentCheque2Input = document.getElementById('paymentCheque2');
        var paymentCheque2 = parseFloat(paymentCheque2Input.value) || 0;
        
        if (document.getElementById('claimpayment').checked) {
            claimPaymentChecked = true;
            paymentCash -= excessAmount;
            paymentCheque -= excessAmount;
            paymentCheque2 -= excessAmount;
        } else {
            if (claimPaymentChecked) {
                paymentCash += excessAmount;
                paymentCheque += excessAmount;
                paymentCheque2 += excessAmount;
            }
        }
        
        paymentCashInput.value = paymentCash.toFixed(2);
        paymentChequeInput.value = paymentCheque.toFixed(2);
        paymentCheque2Input.value = paymentCheque2.toFixed(2);
        
        if (claimPaymentChecked) {
            updateTotalAmount();
        }
    }

    document.getElementById('claimpayment').addEventListener('change', updatePaymentAmounts);

    var radioButtons = document.querySelectorAll('input[name="paymentMethod"]');
    radioButtons.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            claimPaymentChecked = false;
            paymentMethodSelected = true;
            updatePaymentAmounts();
        });
    });
</script>
<script>
    // $(document).ready(function(){
       
    // });
    $(document).ready(function() {
        checkdayendprocess();
        var addcheck='<?php echo $addcheck; ?>';

        $('#invoicedate').change(function(){
            var invdate = $(this).val();
            var refID = $('#ref').val();
            if(refID!=''){
                getdispatchlist(invdate, refID);
            }
        });
        $('#ref').change(function(){
            var refID = $(this).val();
            var invdate = $('#invoicedate').val();

            if (!$('#invoicedate').val()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {  
                getloadlist(invdate, refID);
            }
        });
        $('#area').change(function () {
            var areaID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    areaID: areaID
                },
                url: 'getprocess/getcustomeraccload.php',
                success: function (result) {
                    var objfirst = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';

                    $.each(objfirst, function (i, item) {
                        html += '<option value="' + objfirst[i].id + '" data-type="' + objfirst[i].type + '">';
                        html += objfirst[i].name;
                        html += '</option>';
                    });

                    $('#customer').empty().append(html);
                }
            });
        });
        $('#vehicleload').change(function(){
            var vehicleloadID=$(this).val();
            var selectedStatus = $('#vehicleload option:selected').data('status');
            var invoicedate=$('#invoicedate').val();
            
            $.ajax({
                type: "POST",
                data: {
                    vehicleloadID: vehicleloadID,
                    selectedStatus: selectedStatus
                },
                url: 'getprocess/getareacustomeraccovehicleload.php',
                success: function(result) {
                    var objfirst = JSON.parse(result);
                    var arealist = objfirst.arealist;
                    var cuslist = objfirst.cuslist;

                    var html = '';

                    if (arealist.length > 1) {
                        html += '<option value="">Select</option>';
                    }

                    $.each(arealist, function(i, item) {
                        html += '<option value="' + arealist[i].areaid + '">';
                        html += arealist[i].area;
                        html += '</option>';
                    });

                    $('#area').empty().append(html);

                    var htmlcus = '';
                    htmlcus += '<option value="">Select</option>';
                    $.each(cuslist, function(i, item) {
                        htmlcus += '<option value="' + cuslist[i].customerID + '" data-type="' + cuslist[i].customerType + '">';
                        htmlcus += cuslist[i].customer;
                        htmlcus += '</option>';
                    });

                    $('#customer').empty().append(htmlcus);
                    if (arealist.length > 1) {
                        $('#area').focus();
                    } else {
                        $('#customer').focus();
                    }
                }

            });
            //Get Invoice List
            handleModalHidden();
            // $.ajax({
            //     type: "POST",
            //     data: {
            //         vehicleloadID: vehicleloadID,
            //         invoicedate: invoicedate
            //     },
            //     url: 'getprocess/getinvoiceaccoload.php',
            //     success: function(result) { 
            //         var objfirst = JSON.parse(result);
            //         var html=''; 
            //         $.each(objfirst, function(i, item) {
            //             html+='<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small';
            //             if(objfirst[i].paystatus==1){
            //                 html+=' bg-success-soft';
            //             }
            //             else{
            //                 html+=' pointer clickinvpay';
            //             }
            //             html+='" id="INV-'+objfirst[i].invoiceid+'">INV-' + objfirst[i].invoiceid + '<div>' + parseFloat(objfirst[i].invoicetotal).toFixed(2) + '</div></li>';
            //         });

            //         $('#invoicelist').empty().html(html);
            //         optionpayinvoice();
            //         if($('ul#invoicelist li').length > 0){
            //             $('#btncompleteinvoice').prop('disabled', false);
            //         }
            //     }
            // });
        });

        $('#customer').change(function () {
            var customerID = $(this).val();
            var loadID = $('#vehicleload').val();
            var areaID = $('#area').val();

            $.ajax({
                url: 'getprocess/get_product_prices.php',
                type: 'POST',
                data: {
                    areaID: areaID,
                    customerID: customerID
                },
                dataType: 'json',
                success: function (data) {
                    var tableBody = $('#tableBody');
                    tableBody.empty();

                    tableBody.find('tr').each(function () {
                        updateTotalForRow($(this));
                    });

                    updateGrandTotal();

                    $.each(data, function (index, product) {
                        fetchAvailableQuantity(loadID, product.idtbl_product).then(function (availability) {
                            if (availability.avaqty !== null && availability.avaqty !== undefined && availability.avaqty !== '') {
                                var newsalepricewithVAT = (parseFloat(product.newsaleprice) || 0) * (1 + availability.vatamount / 100);
                                var refillsalepricewithVAT = (parseFloat(product.refillsaleprice) || 0) * (1 + availability.vatamount / 100);
                                var emptysalepricewithVAT = (parseFloat(product.emptysaleprice) || 0) * (1 + availability.vatamount / 100);
                                var encustomer_newprice_withVAT = (parseFloat(product.encustomer_newprice) || 0) * (1 + availability.vatamount / 100);
                                var encustomer_refillprice_withVAT = (parseFloat(product.encustomer_refillprice) || 0) * (1 + availability.vatamount / 100);
                                var encustomer_emptyprice_withVAT = (parseFloat(product.encustomer_emptyprice) || 0) * (1 + availability.vatamount / 100);


                                tableBody.append('<tr>' +
                                    '<td>' + product.product_name + '</td>' +
                                    '<td class="d-none">' + product.idtbl_product + '</td>' +
                                    '<td class="d-none">' + (product.newsaleprice || 0) + '</td>' +
                                    '<td class="d-none">' + (product.refillsaleprice || 0) + '</td>' +
                                    '<td class="d-none">' + (product.emptysaleprice || 0) + '</td>' +
                                    '<td class="d-none">' + (product.encustomer_newprice || 0) + '</td>' +
                                    '<td class="d-none">' + (product.encustomer_refillprice || 0) + '</td>' +
                                    '<td class="d-none">' + (product.encustomer_emptyprice || 0) + '</td>' +
                                    '<td class="d-none">' + newsalepricewithVAT + '</td>' +
                                    '<td class="d-none">' + refillsalepricewithVAT + '</td>' +
                                    '<td class="d-none">' + emptysalepricewithVAT + '</td>' +
                                    '<td class="d-none">' + encustomer_newprice_withVAT + '</td>' +
                                    '<td class="d-none">' + encustomer_refillprice_withVAT + '</td>' +
                                    '<td class="d-none">' + encustomer_emptyprice_withVAT + '</td>' +
                                    '<td class="text-left">' + addCommas(parseFloat(product.newsaleprice).toFixed(2)) + '</td>' +
                                    '<td class="text-left">' + addCommas(parseFloat(product.refillsaleprice).toFixed(2)) + '</td>'+
                                    '<td class="text-left">' + addCommas(parseFloat(product.emptysaleprice).toFixed(2)) + '</td>' +
                                    '<td class="text-left">' + addCommas(parseFloat(product.encustomer_newprice).toFixed(2)) + '</td>' +
                                    '<td class="text-left">' + addCommas(parseFloat(product.encustomer_refillprice).toFixed(2)) + '</td>'+
                                    '<td class="text-left">' + addCommas(parseFloat(product.encustomer_emptyprice).toFixed(2)) + '</td>' +
                                    '<td class="text-center"><input type="number" class="form-control form-control-sm custom-width ' + (availability.avaqty == 0 ? 'bg-danger-soft' : '') + '" name="available_quantity[]" id="avaqty" value="' + availability.avaqty + '" readonly></td>'+
                                    '<td class="text-center"><input type="number" class="form-control form-control-sm custom-width  ' + (availability.emptyqty == 0 ? 'bg-danger-soft' : '') + '" name="ava_empty_quantity[]" id="avaqty" value="' + availability.emptyqty + '" readonly></td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="new_quantity[]" value="0"></td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="refill_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="empty_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="trust_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                    '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="trust_return_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                    '<td class="d-none hide-total-column"><input type="text" class="form-control form-control-sm custom-width" name="hidetotal_quantity[]" value="0"></td>' +
                                    '<td class="text-center"><input type="text" class="form-control form-control-sm custom-width" name="vat_amount[]" id="vat_amount" value="' + availability.vatamount + '%" readonly></td>' +
                                    '<td class="text-right total-column"><input type="text" class="form-control form-control-sm custom-width" name="total_quantity[]" value="0" readonly></td>' +
                                    '<td class="d-none hide-total-column_without_VAT"><input type="number" class="form-control form-control-sm custom-width" name="hidetotal_quantity_without_VAT[]" value="0"></td>' +
                                    '<td class="d-none">' + (product.discount_price || 0) + '</td>' +
                                    '</tr>');

                                    $('.input-integer').on('input', function() {
                                    var inputValue = $(this).val().replace(/\D/g, '');
                                    if (inputValue === '' || inputValue === '0') {
                                        $(this).val('');
                                    } else {
                                        $(this).val(inputValue);
                                    }
                                    });

                                    $('.input-integer').on('blur', function() {
                                        var inputValue = $(this).val().trim();
                                        if (inputValue === '') {
                                            $(this).val('0');
                                        }
                                    });
                            }
                        });
                    });
                },
                error: function (error) {
                    console.log('Error fetching products:', error);
                }
            });

            $('#tableBody').on('keyup', 'input[name^="new_quantity"], input[name^="refill_quantity"], input[name^="empty_quantity"], input[name^="trust_quantity"]', function () {
                var row = $(this).closest('tr');
                var enteredQty = parseFloat($(this).val()) || 0;
                var availableQty = parseFloat(row.find('td:eq(20)').find('input[name^="available_quantity"]').val()) || 0;
                var availableEmptyQty = parseFloat(row.find('td:eq(21)').find('input[name^="ava_empty_quantity"]').val()) || 0;

                var stockInput = $(this);

                if ($(this).attr('name').startsWith('empty_quantity')) {
                    if (enteredQty > availableEmptyQty) {
                        stockInput.removeClass('bg-success-soft');
                        stockInput.addClass('bg-danger-soft');
                        $('#btnissuemodal').prop('disabled', true);
                    } else {
                        stockInput.removeClass('bg-danger-soft');
                        stockInput.addClass('bg-success-soft');
                        $('#btnissuemodal').prop('disabled', false);
                    }
                } else {
                    if (enteredQty > availableQty) {
                        stockInput.removeClass('bg-success-soft');
                        stockInput.addClass('bg-danger-soft');
                        $('#btnissuemodal').prop('disabled', true);
                    } else {
                        stockInput.removeClass('bg-danger-soft');
                        stockInput.addClass('bg-success-soft');
                        $('#btnissuemodal').prop('disabled', false);
                    }
                }

                updateTotalForRow(row);
                updateGrandTotal();
            });

            $('#btnissuemodal').prop('disabled', false);

            $('#tableBody').on('input', 'input[name^="new_quantity"], input[name^="refill_quantity"], input[name^="empty_quantity"], input[name^="trust_quantity"], input[name^="trust_return_quantity"]', function () {
                var row = $(this).closest('tr');
                updateTotalForRow(row);
                updateGrandTotal();
            });
        });

        $("input[name='paymentMethod']").on("change", function() {
            var selectedValue = $(this).val();
            var totalAmount = $("#paymentPayAmount").val();

            if (selectedValue === "1") {
                $("#paymentCash").val(totalAmount);
                $("#paymentCheque").val("");
            } else if (selectedValue === "2") {
                $("#paymentCash").val("");
                $("#paymentCheque").val(totalAmount);
            }
        });

        $('#btnissuemodal').click(function () {
            // Collect data from the form and table
            var invoicedate = $('#invoicedate').val();
            var refID = $('#ref').val();
            var vehicleloadID = $('#vehicleload').val();
            var areaID = $('#area').val();
            var customerID = $('#customer').val();
            var rejectID = $('#reject').val();
            var nettotal = $('#hidetotalinvoice').val();
            var withouttaxtotal = $('#hidetotalinvoicewithoutvat').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
                var productId = $(this).find('td:eq(1)').text();
                var unitprice = $(this).find('td:eq(2)').text();
                var refillprice = $(this).find('td:eq(3)').text();
                var emptyprice = $(this).find('td:eq(4)').text();
                var discountprice = $(this).find('td:eq(31)').text();
                var encustomernewprice = $(this).find('td:eq(5)').text();
                var encustomerrefillprice = $(this).find('td:eq(6)').text();
                var encustomeremptyprice = $(this).find('td:eq(7)').text();
                var newQty = $(this).find('input[name^="new_quantity"]').val();
                var refillQty = $(this).find('input[name^="refill_quantity"]').val();
                var emptyQty = $(this).find('input[name^="empty_quantity"]').val();
                var trustQty = $(this).find('input[name^="trust_quantity"]').val();
                var trustreturnqty = $(this).find('input[name^="trust_return_quantity"]').val();


                orderDetails.push({
                    productId: productId,
                    unitprice: unitprice,
                    refillprice: refillprice,
                    emptyprice: emptyprice,
                    discountprice: discountprice,
                    encustomernewprice: encustomernewprice,
                    encustomerrefillprice: encustomerrefillprice,
                    encustomeremptyprice: encustomeremptyprice,
                    newQty: newQty,
                    refillQty: refillQty,
                    emptyQty: emptyQty,
                    trustQty: trustQty,
                    trustreturnqty: trustreturnqty,
                    
                });
            });

            $.ajax({
                url: 'process/invoiceprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    invoicedate: invoicedate,
                    refID: refID,
                    vehicleloadID: vehicleloadID,
                    areaID: areaID,
                    customerID: customerID,
                    rejectID: rejectID,
                    nettotal: nettotal,
                    withouttaxtotal: withouttaxtotal,
                    orderDetails: orderDetails
                },
                success: function(result) {
                    // location.reload();
                    handleModalHidden();
                    $('#tableBody').empty();
                    $('#customer').val('');
                    $('#divtotal').empty();          

                },
            });
        });
        
        //Invoice Payment
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
                    $('#warningdesc').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please enter the payment value or uncheck full payment | halfpayment check box.</p>');
                    $('#warningModal').modal({
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
        });

        $('#invPaymentCreateBtn').click(function() {
            var table = $("#paymentDetailTable tbody");
            var customerID = '';
            
            table.find('tr').each(function(i, el) {
                var $tds = $(this).find('td');
                customerID = $tds.eq(11).text();

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
        });
        $('input[type=radio][name=paymentMethod]').change(function() {
            if (this.value == '1') {
                $('#paymentCheque').prop("readonly", true);
                $('#paymentChequeNum').prop("readonly", true);
                $('#paymentReceiptNum').prop("readonly", true);
                $('#paymentchequeDate').prop("readonly", true);
                $('#paymentBank').prop("disabled", true);
                $('#paymentCash').prop("readonly", false);
            } else {
                $('#paymentCheque').prop("readonly", false);
                $('#paymentChequeNum').prop("readonly", false);
                $('#paymentReceiptNum').prop("readonly", false);
                $('#paymentchequeDate').prop("readonly", false);
                $('#paymentBank').prop("disabled", false);
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
                var paymentChequeNum = $('#paymentChequeNum').val();
                var paymentReceiptNum = $('#paymentReceiptNum').val();
                var paymentchequeDate = $('#paymentchequeDate').val();
                var paymentBankID = $('#paymentBank').val();
                var paymentBank = $("#paymentBank option:selected").text();
                var paymentCash = $('#paymentCash').val();

                if(paymenttype==1){
                    $('#tblPaymentTypeModal > tbody:last').append('<tr><td>Cash</td><td class="text-right">' + parseFloat(paymentCash).toFixed(2) + '</td><td class="">-</td><td class="">-</td><td>-</td><td>-</td><td>-</td><td class="d-none">1</td><td class="d-none">1</td></tr>');
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
                else{
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
            console.log(myJsonString);

            jsonObjOne = [];
            $("#tblPaymentTypeModal tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObjOne.push(item);
            });

            var myJsonString2 = JSON.stringify(jsonObjOne);
            console.log(myJsonString2);

            var totAmount = $('#paymentPayAmount').val();
            var payAmount = $('#hidePayAmount').val();
            var balAmount = $('#hideBalAmount').val();
            var hidePendingAmount = $('#hidependingamount').val();
            var hiddencustomerID = $('#hiddencustomerID').val();
            var claimStatus = $('#claimpayment').val();
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
        // Call the function when modal is hidden
        $('#modalpaymentreceipt').on('hidden.bs.modal', handleModalHidden);
    });

    // Function to reset form fields
    function resetFormFields() {
        $('#product').val('');
        $('#newprice').val('');
        $('#refillprice').val('');
        $('#emptyprice').val('');
        $('#discountedprice').val('');
        $('#refillqty').val('0');
        $('#emptyqty').val('0');
        $('#newqty').val('0');
        $('#avaqty').val('');
    }

    function handleModalHidden() {
        var vehicleloadID = $('#vehicleload').val();
        var invoicedate = $('#invoicedate').val();

        $.ajax({
            type: "POST",
            data: {
                vehicleloadID: vehicleloadID,
                invoicedate: invoicedate
            },
            url: 'getprocess/getinvoiceaccoload.php',
            success: function(result) {
                var objfirst = JSON.parse(result);
                var html = '';
                $.each(objfirst, function(i, item) {
                    html += '<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small';
                    if (objfirst[i].paystatus == 1) {
                        html += ' bg-success-soft';
                    } else {
                        html += ' pointer clickinvpay';
                    }
                    html += '" id="INV-' + objfirst[i].invoiceid + '">' + objfirst[i].displayedInvoice + '<div>' + parseFloat(objfirst[i].invoicetotal).toFixed(2) + '</div></li>';
                });

                $('#invoicelist').empty().html(html);
                optionpayinvoice();
                if ($('ul#invoicelist li').length > 0) {
                    $('#btncompleteinvoice').prop('disabled', false);
                }
            }
        });
    }

    function fetchAvailableQuantity(loadID, productID) {
        return $.ajax({
            url: 'getprocess/getavaqty.php',
            type: 'POST',
            data: {
                vehicleloadID: loadID,
                productID: productID
            },
            dataType: 'json'
        });
    }
    function updateTotalForRow(row) {
        var newQuantity = parseFloat(row.find('input[name^="new_quantity"]').val()) || 0;
        var refillQuantity = parseFloat(row.find('input[name^="refill_quantity"]').val()) || 0;
        var emptyQuantity = parseFloat(row.find('input[name^="empty_quantity"]').val()) || 0;
        var trustQuantity = parseFloat(row.find('input[name^="trust_quantity"]').val()) || 0;
        var trustreturnQuantity = parseFloat(row.find('input[name^="trust_return_quantity"]').val()) || 0;

        // Prices with VAT
        var newsalepricewithVAT = parseFloat(row.find('td:eq(8)').text()) || 0;
        var refillsalepricewithVAT = parseFloat(row.find('td:eq(9)').text()) || 0;
        var emptysalepricewithVAT = parseFloat(row.find('td:eq(10)').text()) || 0;
        var encustomernewpricewithVAT = parseFloat(row.find('td:eq(11)').text()) || 0;
        var encustomerrefillpricewithVAT = parseFloat(row.find('td:eq(12)').text()) || 0;
        var encustomeremptypricewithVAT = parseFloat(row.find('td:eq(13)').text()) || 0;

        var newsaleprice = parseFloat(row.find('td:eq(2)').text()) || 0;
        var refillsaleprice = parseFloat(row.find('td:eq(3)').text()) || 0;
        var emptysaleprice = parseFloat(row.find('td:eq(4)').text()) || 0;
        var encustomernewprice = parseFloat(row.find('td:eq(5)').text()) || 0;
        var encustomerrefillprice = parseFloat(row.find('td:eq(6)').text()) || 0;
        var encustomeremptyprice = parseFloat(row.find('td:eq(7)').text()) || 0;

        // Check if it's a corporate customer
        if (isCorporateCustomer()) {
            var newTotalWithVAT = newQuantity * encustomernewpricewithVAT;
            var refillTotalWithVAT = refillQuantity * encustomerrefillpricewithVAT;
            var emptyTotalWithVAT = emptyQuantity * encustomeremptypricewithVAT;
            var trustTotalWithVAT = trustQuantity * encustomerrefillpricewithVAT;
            var trustreturnTotalWithVAT = trustreturnQuantity * 0;

            var newTotal = newQuantity * encustomernewprice;
            var refillTotal = refillQuantity * encustomerrefillprice;
            var emptyTotal = emptyQuantity * encustomeremptyprice;
            var trustTotal = trustQuantity * encustomerrefillprice;
            var trustreturnTotal = trustreturnQuantity * 0;

            var totalWithVAT = newTotalWithVAT + refillTotalWithVAT + emptyTotalWithVAT + trustTotalWithVAT + trustreturnTotalWithVAT;
            var totalWithoutVAT = newTotal + refillTotal + emptyTotal + trustTotal + trustreturnTotal;

            var totalColumn = row.find('td:eq(29)');
            var formattedTotal = totalWithVAT.toFixed(2);
            totalColumn.find('input[name^="total_quantity"]').val(formattedTotal);

            var hideTotalColumn = row.find('.hide-total-column');
            hideTotalColumn.find('input[name^="hidetotal_quantity"]').val(totalWithVAT);

            var hideTotalWithoutVATColumn = row.find('.hide-total-column_without_VAT');
            hideTotalWithoutVATColumn.find('input[name^="hidetotal_quantity_without_VAT"]').val(totalWithoutVAT);
        }else{
            var newTotalWithVAT = newQuantity * newsalepricewithVAT;
            var refillTotalWithVAT = refillQuantity * refillsalepricewithVAT;
            var emptyTotalWithVAT = emptyQuantity * emptysalepricewithVAT;
            var trustTotalWithVAT = trustQuantity * refillsalepricewithVAT;
            var trustreturnTotalWithVAT = trustreturnQuantity * 0;

            var newTotal = newQuantity * newsaleprice;
            var refillTotal = refillQuantity * refillsaleprice;
            var emptyTotal = emptyQuantity * emptysaleprice;
            var trustTotal = trustQuantity * refillsaleprice;
            var trustreturnTotal = trustreturnQuantity * 0;
            

            var totalWithVAT = newTotalWithVAT + refillTotalWithVAT + emptyTotalWithVAT + trustTotalWithVAT + trustreturnTotalWithVAT;
            var totalWithoutVAT = newTotal + refillTotal + emptyTotal + trustTotal + trustreturnTotal;

            var totalColumn = row.find('td:eq(29)');
            var formattedTotal = totalWithVAT.toFixed(2);
            totalColumn.find('input[name^="total_quantity"]').val(formattedTotal);

            var hideTotalColumn = row.find('.hide-total-column');
            hideTotalColumn.find('input[name^="hidetotal_quantity"]').val(totalWithVAT);

            var hideTotalWithoutVATColumn = row.find('.hide-total-column_without_VAT');
            hideTotalWithoutVATColumn.find('input[name^="hidetotal_quantity_without_VAT"]').val(totalWithoutVAT);
        }
    }

    // Function to check if the customer is corporate
    function isCorporateCustomer() {
        var customerType = $('#customer').find(':selected').data('type');
        return customerType === 1;
    }

    function isCorporateCustomer() {
        var customerType = $('#customer').find(':selected').data('type');
        return customerType === 1;
    }

    function updateGrandTotal() {
        var grandTotal = 0;
        var grandTotalVAT = 0;

        $('#tableBody').find('input[name^="total_quantity"]').each(function () {
            var total = parseFloat($(this).val().replace(/,/g, '')) || 0;
            grandTotal += total;
        });

        $('#tableBody').find('input[name^="hidetotal_quantity_without_VAT"]').each(function () {
            var total = parseFloat($(this).val().replace(/,/g, '')) || 0;
            grandTotalVAT += total;
        });

        $('#divtotal').text('Rs. ' + addCommas(grandTotal.toFixed(2)));
        $('#hidetotalinvoice').val(grandTotal);
        $('#hidetotalinvoicewithoutvat').val(grandTotalVAT);
    }

    function getloadlist(invdate, refID){
        $.ajax({
            type: "POST",
            data: {
                invdate: invdate,
                refID: refID
            },
            url: 'getprocess/getloadaccoref.php',
            success: function(result) { 
            var objfirst = JSON.parse(result);
            var html = '';
            html += '<option value="">Select</option>';
            
            $.each(objfirst, function(i, item) {
                html += '<option value="' + objfirst[i].id + '" data-status="' + objfirst[i].status + '">';
                html += 'VL-' + objfirst[i].id + '/' + objfirst[i].vehicle;
                html += '</option>';
            });

            $('#vehicleload').empty().append(html);
        }
        });
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

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function optionpayinvoice(){
        $('.clickinvpay').click(function(){
            var invID = $(this).attr('id');

            $('#modalinvoicepayment').modal('show');

            $('#invoiceviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

            $.ajax({
                type: "POST",
                data: {
                    invoiceno: invID
                },
                url: 'getprocess/getinvoicepayment.php',
                success: function(result) {//alert(result);
                    $('#invoiceviewdetail').html(result);
                    $('#invPaymentCheckBtn').prop("disabled", false);
                    tblcheckboxevent();
                }
            });
        });
    }
    
    function tblcheckboxevent() {
        $('#paymentDetailTable tbody').on('click', '.fullAmount', function() {
            var row = $(this);
            if ((row.closest('.fullAmount')).is(':checked')) {
                var fullAmount = row.closest("tr").find('td:eq(5)').text();
                row.closest("tr").find('td:eq(10)').text(fullAmount);
                row.closest("tr").find('td:eq(9)').find('input[type="checkbox"]').prop('checked', false);
            } else {
                row.closest("tr").find('td:eq(10)').text('0.00');
                row.closest("tr").find('td:eq(9)').find('input[type="checkbox"]').prop('checked', false);
            }
        });

        $('#paymentDetailTable tbody').on('click', '.halfAmount', function() {
            var row = $(this);
            if ((row.closest('.halfAmount')).is(':checked')) {
                tblpayamount();
                row.closest("tr").find('td:eq(8)').find('input[type="checkbox"]').prop('checked', false);
            } else {
                tblTextRemove();
                row.closest("tr").find('td:eq(10)').text('0.00');
                row.closest("tr").find('td:eq(8)').find('input[type="checkbox"]').prop('checked', false);
            }
        });
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

                $('<input type="Text" class="form-control form-control-sm editfieldpay">').val(val).appendTo($this);
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

    function availableqtyoption(){
        $('#submitavabtn').click(function(){
            var customerID = $('#customer').val();
            var invoicedate = $('#invoicedate').val();
            var rejectID = $('#reject').val();
            var avaproduct=$("input[name='cusavaproduct\\[\\]']").map(function(){return $(this).val();}).get();
            var avafullqty=$("input[name='cusavafull\\[\\]']").map(function(){return $(this).val();}).get();
            var avaemptyqty=$("input[name='cusavaempty\\[\\]']").map(function(){return $(this).val();}).get();
            var avabufferqty=$("input[name='cusavabuffer\\[\\]']").map(function(){return $(this).val();}).get();

            $.ajax({
                type: "POST",
                data: {
                    customerID: customerID,
                    rejectID: rejectID,
                    avaproduct: avaproduct,
                    avafullqty: avafullqty,
                    avaemptyqty: avaemptyqty,
                    avabufferqty: avabufferqty,
                    invoicedate: invoicedate
                },
                url: 'process/customeravailableqtyprocess.php',
                success: function(result) {
                    var objava = JSON.parse(result);

                    if(objava.actiontype==1){
                        $('#customeravastock').collapse('hide');
                        $('#invoiceinfo').collapse('show');
                    }
                    action(objava.action);
                }
            });
        });
    }
    function checkdayendprocess(){
        $.ajax({
            type: "POST",
            data: {
                
            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                }
                else if(result==0){
                    $('#viewmessage').html("Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
