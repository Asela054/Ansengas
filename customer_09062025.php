<?php 
include "include/header.php";  

$sql="SELECT `idtbl_customer`, `name`, `nic`, `phone`, `status`, `type`, `tbl_area_idtbl_area` FROM `tbl_customer` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlproductaddrep="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproductaddrep =$conn-> query($sqlproductaddrep); 

$sqlsalesrep="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7";
$resultsalesrep =$conn-> query($sqlsalesrep); 

$productarray=array();
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 
while ($rowproduct = $resultproduct-> fetch_assoc()) {
    $obj=new stdClass();
    $obj->productID=$rowproduct['idtbl_product'];
    $obj->product=$rowproduct['product_name'];

    array_push($productarray, $obj);
}

$arealistarray=array();
$sqlarea="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarea =$conn-> query($sqlarea); 
if($resultarea->num_rows > 0) {
    while ($rowarea = $resultarea-> fetch_assoc()) {
        $obj=new stdClass();
        $obj->idtbl_area=$rowarea['idtbl_area'];
        $obj->area=$rowarea['area'];

        array_push($arealistarray, $obj);
    }
}

$sqlmainarea="SELECT `idtbl_main_area`, `main_area` FROM `tbl_main_area` WHERE `status`=1";
$resultmainarea =$conn-> query($sqlmainarea); 

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer =$conn-> query($sqlcustomer); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

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
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            <span>Customer</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="process/customerprocess.php" method="post" autocomplete="off">

                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Name of Shop/Company*</label>
                                            <input type="text" class="form-control form-control-sm" id="cmpName" name="cmpName" required>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Alias Name</label>
                                            <input type="text" class="form-control form-control-sm" id="alias_name" name="alias_name">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row mb-1">
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">PV Num</label>
                                            <input type="text" class="form-control form-control-sm" id="pv_num" name="pv_num" placeholder="">
                                        </div>
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Name of Owner/Director*</label>
                                            <input type="text" class="form-control form-control-sm" id="owner_name" name="owner_name" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Customer Type*</label>
                                            <select name="cusType" id="cusType" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <option value="1">Commercial</option>
                                                <option value="2">Dealer</option>
                                            </select>
                                        </div>                            
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">NIC</label>
                                            <input type="text" class="form-control form-control-sm" id="cusNic" name="cusNic" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Contact*</label>
                                            <input type="text" class="form-control form-control-sm" id="cusContact" name="cusContact" required>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Area*</label>
                                            <select name="cusArea" id="cusArea" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select</option>
                                                <?php foreach($arealistarray as $areadatalist) { ?>
                                                <option value="<?php echo $areadatalist->idtbl_area ?>">
                                                    <?php echo $areadatalist->area ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Other Area List</label>
                                        <select name="cusAreaOther[]" id="cusAreaOther" class="form-control form-control-sm" multiple>
                                            <?php foreach($arealistarray as $areadatalist) { ?>
                                            <option value="<?php echo $areadatalist->idtbl_area ?>">
                                                <?php echo $areadatalist->area ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address of Shop/Company</label>
                                        <textarea class="form-control form-control-sm" id="cmpAddress" name="cmpAddress"></textarea>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address of Owner/Director</label>
                                        <textarea class="form-control form-control-sm" id="ownAddress" name="ownAddress"></textarea>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Feq. No*</label>
                                            <input type="text" class="form-control form-control-sm" id="feqno" name="feqno" readonly>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">DOB of Owner/Director</label>
                                            <input type="date" class="form-control form-control-sm" id="owner_dob" name="owner_dob">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Tax Num</label>
                                            <input type="text" class="form-control form-control-sm" id="cusTaxNum" name="cusTaxNum" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Email</label>
                                            <input type="email" class="form-control form-control-sm" id="cusEmail" name="cusEmail">
                                        </div>
                                    </div>
                                    <div class="form-group mb-1" style="display: none;">
                                        <label class="small font-weight-bold text-dark">S-Vat</label>
                                        <input type="text" class="form-control form-control-sm" id="cusSVat" name="cusSVat" placeholder="">
                                    </div>
                                    
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Credit Type</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" id="cuscredittype" name="cuscredittype">
                                                    <option value="">Select</option>
                                                    <option value="1">Bill To Bill</option>
                                                    <option value="2">Credit Days</option>
                                                    <option value="3">Cash</option>
                                                </select>
                                                <input type="text" class="form-control" id="cuscreditdays" name="cuscreditdays" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Credit Limit</label>
                                            <input type="text" class="form-control form-control-sm" id="cusCreditlimit" name="cusCreditlimit" placeholder="">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">No of visit days</label>
                                            <input type="text" class="form-control form-control-sm" id="cusNoVisit" name="cusNoVisit" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Day</label>
                                            <select name="cusVisitDays[]" id="cusVisitDays" class="form-control form-control" multiple>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                                <option value="7">Sunday</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div class="form-row">
                                        <div class="col-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="vat_status"
                                                    id="vat_status" value="1">
                                                <label class="custom-control-label small font-weight-bold text-dark"
                                                    for="vat_status">VAT Status</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    name="discounted_customer" id="discounted_customer" value="1">
                                                <label class="custom-control-label small font-weight-bold text-dark"
                                                    for="discounted_customer">Discounted Customer</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    name="special_customer" id="special_customer" value="1">
                                                <label class="custom-control-label small font-weight-bold text-dark"
                                                    for="special_customer">Special Customer</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1 mt-2">
                                        <div class="col-6" id="dropdownContainer" style="display: none;">
                                            <label class="small font-weight-bold text-dark">Main Area</label>
                                            <select name="special_area" id="special_area"
                                                class="form-control form-control-sm">
                                                <option value="">Select</option>
                                                <?php if($resultmainarea->num_rows > 0) {while ($rowmainarea = $resultmainarea-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowmainarea['idtbl_main_area'] ?>">
                                                    <?php echo $rowmainarea['main_area'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <h6 class="large title-style mt-5"><span>Tax Information</span></h6><br>
                                	<div class="form-row mb-1">
                                		<div class="col">
                                			<label class="small font-weight-bold text-dark">Tax Customer Name</label>
                                			<input type="text" class="form-control form-control-sm" name="tax_cus_name"
                                				id="tax_cus_name">
                                		</div>
                                	</div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-8">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Feq. No</th>
                                                <th>Area</th>
                                                <th>Name</th>
                                                <th>Alias Name</th>
                                                <th>Type</th>
                                                <th>NIC</th>
                                                <th>Contact</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!--Modal of Customer Contact Person -->
<div class="modal fade" id="tableModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tableModalLabel">Customer Contact Person</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contactlist"></div>
                </div>
            </div>
        </div>    
    </div>
</div>
<!-- Modal Product Price List For Co-operate Customer -->
<div class="modal fade" id="modaladdproductprice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="addproductform" autocomplete="off">
                            <div class="form-row">
                                <div class="col-5">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productlist" id="productlist" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($productarray as $rowprocutlist) { ?>
                                        <option value="<?php echo $rowprocutlist->productID ?>"><?php echo $rowprocutlist->product ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">New Sale Price*</label>
                                    <input type="text" class="form-control form-control-sm" id="newsaleprice" name="newsaleprice" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Refill Sale Price*</label>
                                    <input type="text" class="form-control form-control-sm" id="refillsaleprice" name="refillsaleprice" required>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="submitmodalBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hidesubmit" value="">
                            </div>
                            <input type="hidden" name="hidecusid" id="hidecusid" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div id="viewenterlist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Product Stock Qty For Retail Customer -->
<div class="modal fade" id="modaladdproductstock" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product Buffer Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="addproductstockform" autocomplete="off">
                            <div class="form-row">
                                <div class="col-7">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productliststock" id="productliststock" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($productarray as $rowprocutlist) { ?>
                                        <option value="<?php echo $rowprocutlist->productID ?>"><?php echo $rowprocutlist->product ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Full Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="fullqty" name="fullqty" required>
                                </div>
                                <!-- <div class="col">
                                    <label class="small font-weight-bold text-dark">Empty Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="emptyqty" name="emptyqty" required>
                                </div> -->
                                <input type="hidden" class="form-control form-control-sm" id="emptyqty" name="emptyqty" value="0">
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="submitstockmodalBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hidestocksubmit" value="">
                            </div>
                            <input type="hidden" name="hidestockcusid" id="hidestockcusid" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div id="viewenterstocklist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Customer Shop Close -->
<div class="modal fade" id="modalshopclose" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Close Dealer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <form id="dealercloseform">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Customer*</label>
                                    <select name="customerclose" id="customerclose" class="form-control form-control-sm" disabled>
                                        <option value="">Select</option>
                                        <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                    <input type="hidden" name="hidecustomerclose" id="hidecustomerclose" value="">
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="productclose" id="productclose" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Full Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="fullqtyclose" name="fullqtyclose" value="0" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Empty Qty*</label>
                                    <input type="text" class="form-control form-control-sm" id="emtyqtyclose" name="emtyqtyclose" value="0" required>
                                </div>
                            </div>
                            <input type="hidden" name="hidenewprice" id="hidenewprice" value="">
                            <input type="hidden" name="hideemptyprice" id="hideemptyprice" value="">
                            <div class="form-group mt-2">
                                <button type="button" id="submitbtnclose" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                <input type="submit" class="d-none" id="hideclosesubmit" value="">
                            </div>
                        </form>
                    </div>
                    <div class="col-9">
                        <table class="table table-striped table-bordered table-sm" id="tableclosecustomer">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">New Price</th>
                                    <th class="d-none">Empty Price</th>
                                    <th class="text-center">Full Qty</th>
                                    <th class="text-center">Empty Qty</th>
                                    <th class="d-none">Total</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right"><h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1></div>
                            <input type="hidden" id="hidetotalinvoice" value="0">
                        </div>
                        <div class="row">
                            <div class="col-6">&nbsp;</div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm fa-pull-right mt-2 px-4" id="btndealerclose">Dealer Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Customer Shop Close View -->
<div class="modal fade" id="contactlistview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Close Dealer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="dealercloseviewinfo"></div>                 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Contact Person -->
<div class="modal fade" id="accountmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel"><i class="fas fa-user"></i> ADD CONTACT PERSON</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="addcontactpersonform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Contact Person for Office</label>
                                <input type="text" class="form-control form-control-sm" name="company_contact_person" id="company_contact_person">
                            </div>
                            <div class="form-group mb-1">
                                <input type="hidden" class="form-control form-control-sm" name="hiddenid" id="hiddenid"
                                    required>
                                <label class="small font-weight-bold">Contact Name*</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name" required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Contact Type*</label>
                                <input type="text" class="form-control form-control-sm" name="type" id="type">
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Mobile*</label>
                                <input type="text" class="form-control form-control-sm" name="mobile" id="mobile"
                                    required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Phone</label>
                                <input class="form-control form-control-sm" name="phone" id="phone">
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">WhatsApp </label>
                                <input type="text" class="form-control form-control-sm" name="whatsapp_num" id="whatsapp_num"
                                    required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Email</label>
                                <input class="form-control form-control-sm" name="mail" id="mail">
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">DOB of Contact Person</label>
                                <input type="date" class="form-control form-control-sm" name="contact_person_dob" id="contact_person_dob"
                                    required>
                            </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" id="submitBtn2" class="btn btn-primary btn-sm px-4"><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                        <input type="submit" class="d-none" id="hidesubmit2" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add SalesRep -->
<div class="modal fade" id="addsalesrepmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel"><i class="fas fa-user-plus"></i> ADD EXECUTIVES</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="addsalesrepform" autocomplete="off">
                            <div class="form-group mb-1">
                                <input type="hidden" class="form-control form-control-sm" id="hiddenidrep" name="hiddenidrep">
                            </div>
                            <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label><br>
                                        <select class="form-control form-control-sm" name="product[]" id="product" style="width:100%;" required multiple>
                                            <option value="">Select</option>
                                            <?php if($resultproductaddrep->num_rows > 0) {while ($rowproduct = $resultproductaddrep-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Executive Name*</label>
                                        <select class="form-control form-control-sm" name="salesrep" id="salesrep" required>
                                            <option value="">Select</option>
                                            <?php if($resultsalesrep->num_rows > 0) {while ($rowsalesrep = $resultsalesrep-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowsalesrep['idtbl_employee'] ?>"><?php echo $rowsalesrep['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" id="submitBtnRep" class="btn btn-primary btn-sm px-4"><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                        <input type="submit" class="d-none" id="hidesubmitrep" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#special_customer').change(function () {
            if ($(this).is(':checked')) {
                $('#dropdownContainer').show();
            } else {
                $('#dropdownContainer').hide();
                // Clear dropdown values
                $('#special_area').val('');
            }
        });

        $("#product").select2();
        $("#cusAreaOther").select2();
        $("#cusVisitDays").select2();

        $('#cuscredittype').change(function(){
            var type = $(this).val();
            if(type==2){
                $('#cuscreditdays').prop('readonly', false);
            }
            else{
                $('#cuscreditdays').prop('readonly', true);
            }
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            dom: "<'row'<'col-sm-2'B><'col-sm-2'l><'col-sm-8'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All'],
			],
            "buttons": [{
					extend: 'csv',
					className: 'btn btn-success btn-sm',
					title: 'Product Information',
					text: '<i class="fas fa-file-csv mr-2"></i> CSV',
				},
				// 'csv'
			],
            ajax: {
                url: "scripts/customerlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_customer"
                },
                {
                    "data": "feqno"
                },
                {
                    "data": "area"
                },
                {
                    "data": "name"
                },
                {
                    "data": "alias_name"
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        var html = '';
                        if(full['type']==1){
                            html+='Co-operate';
                        }
                        else if(full['type']==2){
                            html+='Retail';
                        }
                        else{
                            html+='Laugfs Agent';
                        }

                        return html;     
                    }
                },
                {
                    "data": "nic"
                },
                {
                    "data": "phone"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-orange btn-sm btnaddsalesrep mr-1" id="'+full['idtbl_customer']+'" data-toggle="tooltip" data-placement="bottom" title="Add Salesrep"><i class="fas fa-user-plus"></i></button>';

                        button+='<button class="btn btn-outline-secondary btn-sm btnaddacount mr-1" id="'+full['idtbl_customer']+'"><i class="fas fa-user"></i></button>';

                        button+='<button class="btn btn-outline-info btn-sm mr-1 btncontactview mr-1" id="'+full['idtbl_customer']+'"><i class="fas fa-address-book"></i></button>';

                        if(full['type']==2){
                            button+='<button class="btn btn-outline-dark btn-sm btnAddProductStock mr-1 ';if(addcheck==0){button+='d-none';}button+='" id="'+full['idtbl_customer']+'"><i class="fas fa-warehouse"></i></button>';
                        }
                        if(full['type']==1 | full['type']==3){
                            button+='<button class="btn btn-outline-purple btn-sm btnAddProduct mr-1 ';if(addcheck==0){button+='d-none';}button+='" id="'+full['idtbl_customer']+'"><i class="fas fa-shopping-cart"></i></button>';
                        }

                        if(editcheck=1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_customer']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="process/statuscustomer.php?record='+full['idtbl_customer']+'&type=2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            button+='<button type="button" class="btn btn-outline-pink btn-sm mr-1 btnclose ';if(deletecheck==0){button+='d-none';}button+='" id="'+full['idtbl_customer']+'"><i class="fas fa-times-circle"></i></button>';
                            button+='<button data-url="process/statuscustomer.php?record='+full['idtbl_customer']+'&type=4"  data-actiontype="4" class="btn btn-outline-dark btn-sm mr-1 btntableaction"><i class="far fa-calendar-check"></i></button>';
                        }else if(full['status']==2 && statuscheck!=5){
                            button+='<button type="button" data-url="process/statuscustomer.php?record='+full['idtbl_customer']+'&type=1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        else if(full['status']==5){
                            button+='<button type="button" class="btn btn-outline-purple btn-sm mr-1 btncloseview" id="'+full['idtbl_customer']+'"><i class="fas fa-file"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="process/statuscustomer.php?record='+full['idtbl_customer']+'&type=3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnEdit', async function() {
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcustomer.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#cusType').val(obj.type);  
                        $('#cmpName').val(obj.name);
                        $('#pv_num').val(obj.pv_num);
                        $('#owner_name').val(obj.owner_name);
                        $('#owner_dob').val(obj.owner_dob);
                        $('#cusNic').val(obj.nic);
                        $('#cusContact').val(obj.phone);
                        $('#cusEmail').val(obj.email);
                        $('#cmpAddress').val(obj.address);
                        $('#ownAddress').val(obj.owner_address);
                        $('#tax_cus_name').val(obj.tax_cus_name);
                        $('#vat_status').prop('checked', !!parseInt(obj.vat_status));
                        $('#discounted_customer').prop('checked', !!parseInt(obj.discount_status));
                        $('#cusTaxNum').val(obj.tax_num);
                        $('#alias_name').val(obj.alias_name);
                        $('#cusArea').val(obj.area);
                        $('#cusNoVisit').val(obj.nodays);
                        $('#cusCreditlimit').val(obj.credit);
                        $('#cuscredittype').val(obj.credittype);
                        $('#special_customer').prop('checked', !!parseInt(obj.specialcusstatus)).change();
                        $('#special_area').val(obj.mainarea);
                        $('#feqno').val(obj.feqno).prop('readonly', false);
                        $('#feqno').prop('required', true);

                        if(obj.credittype==2){
                            $('#cuscreditdays').prop('readonly', false);
                        }
                        else{
                            $('#cuscreditdays').prop('readonly', true);
                        }

                        var dayslist = obj.dayslist;
                        var dayslistoption = [];
                        $.each(dayslist, function(i, item) {
                            dayslistoption.push(dayslist[i].daysID);
                        });

                        $('#cusVisitDays').val(dayslistoption);
                        $('#cusVisitDays').trigger('change');

                        var otherarealist = obj.otherarealist;
                        var otherarealistoption = [];
                        $.each(otherarealist, function(i, item) {
                            otherarealistoption.push(otherarealist[i].otherareaID);
                        });

                        $('#cusAreaOther').val(otherarealistoption);
                        $('#cusAreaOther').trigger('change');

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnaddsalesrep', function() {
                var id = $(this).attr('id');
                $("#hiddenidrep").val(id);

                $('#addsalesrepmodal').modal('show');

        });
        $('#submitBtnRep').click(function(){
            if (!$("#addsalesrepform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitrep").click();
            } else {   
                var product = $('#product').val();
                var salesrep = $('#salesrep').val();
                var hiddenID = $('#hiddenidrep').val();

                $.ajax({
                    type: "POST",
                    data: {
                        product: product,
                        salesrep: salesrep,
                        hiddenID: hiddenID

                    },
                    url: 'process/addcustomerrepprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnAddProduct', function() {
            var id = $(this).attr('id'); 
            loadproductpricelist(id);
            $('#hidecusid').val(id);
            $('#modaladdproductprice').modal('show');
        });
        $('#submitmodalBtn').click(function(){
            if (!$("#addproductform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var productID = $('#productlist').val();
                var newsaleprice = $('#newsaleprice').val();
                var refillsaleprice = $('#refillsaleprice').val();
                var hidecusid = $('#hidecusid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        newsaleprice: newsaleprice,
                        refillsaleprice: refillsaleprice,
                        hidecusid: hidecusid
                    },
                    url: 'process/customerproductpriceprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        loadproductpricelist(hidecusid);

                        $('#productlist').val('');
                        $('#newsaleprice').val('');
                        $('#refillsaleprice').val('');
                    }
                });
            }
        });
        $('#submitBtn2').click(function(){
            if (!$("#addcontactpersonform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit2").click();
            } else {   
                var company_contact_person = $('#company_contact_person').val();
                var name = $('#name').val();
                var type = $('#type').val();
                var mobile = $('#mobile').val();
                var phone = $('#phone').val();
                var whatsapp_num = $('#whatsapp_num').val();
                var mail = $('#mail').val();
                var contact_person_dob = $('#contact_person_dob').val();
                var hiddenid = $('#hiddenid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        company_contact_person: company_contact_person,
                        name: name,
                        type: type,
                        mobile: mobile,
                        phone: phone,
                        whatsapp_num: whatsapp_num,
                        mail: mail,
                        contact_person_dob: contact_person_dob,
                        hiddenid: hiddenid
                    },
                    url: 'process/customercontactpersonprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                    }
                });
            }
        });
        $('#modaladdproductprice').on('hidden.bs.modal', function (e) {
            $('#viewenterlist').html('');
        });

        $('#dataTable tbody').on('click', '.btnaddacount', function() {
                var id = $(this).attr('id');
                $("#hiddenid").val(id);

                $('#accountmodal').modal('show');

        });

        $('#dataTable tbody').on('click', '.btnAddProductStock', function() {
            var id = $(this).attr('id'); 
            loadproductstocklist(id);
            $('#hidestockcusid').val(id);
            $('#modaladdproductstock').modal('show');
        });
        $('#submitstockmodalBtn').click(function(){
            if (!$("#addproductstockform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidestocksubmit").click();
            } else {   
                var productID = $('#productliststock').val();
                var fullqty = $('#fullqty').val();
                var emptyqty = $('#emptyqty').val();
                var hidecusid = $('#hidestockcusid').val();

                $.ajax({
                    type: "POST",
                    data: {
                        productID: productID,
                        fullqty: fullqty,
                        emptyqty: emptyqty,
                        hidecusid: hidecusid
                    },
                    url: 'process/customerproductstockprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        loadproductstocklist(hidecusid);

                        $('#productliststock').val('');
                        $('#fullqty').val('');
                        $('#emptyqty').val('');
                    }
                });
            }
        });

        //Customer contact person 
        $('#dataTable tbody').on('click', '.btncontactview', function() {
            var  recordID = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                     recordID:  recordID
                },
                url: 'getprocess/getcontactperson.php',
                success: function(result) {
                    $('#contactlist').html(result);
                    $('#tableModal').modal('show');
                    optionilist( recordID);
                }
            });
        });


        // Close shop
        $('#dataTable tbody').on('click', '.btnclose', function() {
            var id = $(this).attr('id');
            $('#customerclose').val(id);
            $('#hidecustomerclose').val(id);
            $('#modalshopclose').modal('show');
        });
        $('#productclose').change(function(){
            var productID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    productID: productID
                },
                url: 'getprocess/getclosepricesaccoproduct.php',
                success: function(result) {//alert(result);
                    var obj = JSON.parse(result);
                    $('#hidenewprice').val(obj.newsaleprice);
                    $('#hideemptyprice').val(obj.emptyprice);

                    $('#fullqtyclose').focus();
                    $('#fullqtyclose').select();
                }
            });   
        });
        $("#submitbtnclose").click(function() {
            if (!$("#dealercloseform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideclosesubmit").click();
            } else {   
                var productID = $('#productclose').val();
                var product = $("#productclose option:selected").text();
                var newprice = parseFloat($('#hidenewprice').val());
                var emptyprice = parseFloat($('#hideemptyprice').val());
                var fullqty = parseFloat($('#fullqtyclose').val());
                var emptyqty = parseFloat($('#emtyqtyclose').val());

                var newtotal = parseFloat(newprice*fullqty);
                var emptytotal = parseFloat(emptyprice*emptyqty);

                var total = parseFloat(newtotal+emptytotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tableclosecustomer > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + newprice + '</td><td class="d-none">' + emptyprice + '</td><td class="text-center">' + fullqty + '</td><td class="text-center">' + emptyqty + '</td><td class="total d-none">' + total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                $('#productclose').val('');
                $('#hidenewprice').val('');
                $('#hideemptyprice').val('');
                $('#fullqtyclose').val('0');
                $('#emtyqtyclose').val('0');

                var sum = 0;
                $(".total").each(function(){
                    sum += parseFloat($(this).text());
                });
                
                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. '+showsum);
                $('#hidetotalinvoice').val(sum);
                $('#productclose').focus();
            }
        }); 
        $('#tableclosecustomer').on( 'click', 'tr', async function() {
            var r = await Otherconfirmation("You want to remove this product ?");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function(){
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));
                
                $('#divtotal').html('Rs. '+showsum);
                $('#hidetotalinvoice').val(sum);
                $('#productclose').focus();
            }
        });
        $('#btndealerclose').click(function(){
            jsonObj = [];
            $("#tableclosecustomer tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            var customer = $('#hidecustomerclose').val();
            var total = $('#hidetotalinvoice').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    customer: customer,
                    total: total
                },
                url: 'process/customercloseprocess.php',
                success: function(result) { //alert(result);
                    $('#modalshopclose').modal('hide');
                    action(result);
                    location.reload();
                }
            });
        });
        $('#dataTable tbody').on('click', '.btncloseview', function() {
            var id = $(this).attr('id');
            
            $.ajax({
                type: "POST",
                data: {
                    customerID: id
                },
                url: 'getprocess/getclosedealerinformation.php',
                success: function(result) {//alert(result);
                    $('#dealercloseviewinfo').html(result);
                    $('#modalshopcloseview').modal('show');
                }
            });  
        });
    });
    function loadproductpricelist(cusID){
        var deletecheck = '<?php echo $deletecheck; ?>';
        $.ajax({
            type: "POST",
            data: {
                cusID: cusID,
                deletecheck: deletecheck
            },
            url: 'getprocess/getproductpriceaccocustomer.php',
            success: function(result) { //alert(result);
                $('#viewenterlist').html(result);
                loadlistoption(cusID);
            }
        });
    }
    function loadlistoption(cusID){
        $('#tableproductlist tbody').on('click', '.btnremoveproduct', async function() {
            var r = await Otherconfirmation("You want to Remove this ?");
            if (r == true) {
                var id = $(this).attr('id'); 
                $.ajax({
                    type: "POST",
                    data: {
                        cusproductID: id
                    },
                    url: 'process/statuscustomerproductprice.php',
                    success: function(result) { //alert(result);
                        action(result);
                        loadproductpricelist(cusID)
                    }
                });
            }
        });
    }
    function loadproductstocklist(cusID){ 
        var deletecheck = '<?php echo $deletecheck; ?>';
        $.ajax({
            type: "POST",
            data: {
                cusID: cusID,
                deletecheck: deletecheck
            },
            url: 'getprocess/getproductstockaccocustomer.php',
            success: function(result) { //alert(result);
                $('#viewenterstocklist').html(result);
                loadstocklistoption(cusID);
            }
        });
    }
    function loadstocklistoption(cusID){
        $('#tablestockproductlist tbody').on('click', '.btnremovestockproduct', async function() {
            var r = await Otherconfirmation("You want to Remove this ?");
            if (r == true) {
                var id = $(this).attr('id'); 
                $.ajax({
                    type: "POST",
                    data: {
                        cusproductID: id
                    },
                    url: 'process/statuscustomerproductstock.php',
                    success: function(result) { //alert(result);
                        action(result);
                        loadproductstocklist(cusID)
                    }
                });
            }
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
</script>
<?php include "include/footer.php"; ?>
