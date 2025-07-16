<?php 
include "include/header.php"; 

$productgaslist=array();
$gasProducts = $conn->query("SELECT idtbl_product, product_name FROM tbl_product 
WHERE tbl_product_category_idtbl_product_category = 1 
AND status = 1 ORDER BY orderlevel");
while ($product = $gasProducts->fetch_assoc()) {
    $productgaslist[] = $product['product_name'];
}

$productaccesorieslist=array();
$accessories = $conn->query("SELECT idtbl_product, product_name FROM tbl_product 
WHERE tbl_product_category_idtbl_product_category = 2 
AND status = 1");
while ($accessory = $accessories->fetch_assoc()) {
    $productaccesorieslist[] = $accessory['product_name'];
}

include "include/topnavbar.php"; 
?>
<style>
.watermarked-card {
    position: relative;
}

.watermarked-card::before {
    content: "";
    position: absolute;
    top: 20rem;
    left: 30rem;
    width: 45rem;
    height: 45rem;
    background-image: url('images/logo.png');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center center;
    opacity: 0.1;
}

/* Container for scrollable table */
.table-container {
  overflow-x: auto;
  max-width: 100%;
}

/* Table styling */
table {
  border-collapse: separate; /* Required for sticky to work */
  border-spacing: 0;
}

/* Frozen first column */
th:first-child,
td:first-child {
  position: sticky;
  left: 0;
  background: #FFFFFF; /* Crucial - keeps original background */
  z-index: 2;
}

/* Header row styling */
thead th {
  position: sticky;
  top: 0;
  background: #f8f8f8; /* Set your preferred header color */
  z-index: 3;
}

/* Special case for rowspan header */
th[rowspan] {
  z-index: 4; /* Highest priority */
  background: #f8f8f8; /* Match header color */
}
</style>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="activity"></i></div>
                                    <span>Dashboard</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body watermarked-card">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col">
                                <div class="card shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Monthly Sale Summary</span></h6>
                                        <div id="waitload"></div>
                                        <canvas id="salechart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Upcoming Birthdays</span></h6>
                                        <span class="badge bg-warning-soft px-2 mt-2">&nbsp;</span> Employee birthday
                                        <span class="badge bg-danger-soft px-2 mt-2">&nbsp;</span> Today birthday
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-sriped table-bordered table-sm small mt-2" id="bdayTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th nowrap>Dealer</th>
                                                        <th nowrap>Name</th>
                                                        <th nowrap>DOB</th>
                                                        <th nowrap>Mobile</th>
                                                        <th nowrap>Address</th>
                                                        <th nowrap>Area</th>
                                                        <th nowrap>Excetive</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Vehicle stock summery on <?php echo date('Y-m-d') ?></span></h6>
                                        <span class="badge bg-success-soft px-2 mt-2">&nbsp;</span> Unload vehicle load
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-striped table-bordered table-sm small mt-3" id="loadstocktable">
                                                <thead class="thead-dark">
                                                    <tr class="">
                                                        <th rowspan="2" class="align-middle">Vehicle</th>
                                                        <?php foreach($productgaslist as $rowproductgas){ ?>
                                                        <th colspan="3" class="text-center"><?php echo $rowproductgas; ?></th>
                                                        <?php } ?>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th colspan="3" class="text-center"><?php echo $rowproductaccesories; ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr class="">
                                                        <?php foreach($productgaslist as $rowproductgas){ ?>
                                                        <th class="text-center">Load</th>
                                                        <th class="text-center">Sale</th>
                                                        <th class="text-center">Balance</th>
                                                        <?php } ?>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th class="text-center">Load</th>
                                                        <th class="text-center">Sale</th>
                                                        <th class="text-center">Balance</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Stock summery on <?php echo date('Y-m-d') ?></span></h6>
                                        <table class="table table-striped table-bordered table-sm small" id="mainstocksummerytable">
                                            <thead class="thead-dark">
                                                <tr class="">
                                                    <th>Gas & Accessories</th>
                                                    <th class="text-center">Full Qty</th>
                                                    <th class="text-center">Empty Qty</th> 
                                                    <th class="text-center">Damage Qty</th> 
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">   
                                <div class="card shadow-none">
                                    <div class="card-body">                        
                                        <h6 class="small title-style"><span>Sales executive target on <?php echo date('F Y') ?></span></h6>
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-striped table-bordered table-sm small" id="executiveTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th nowrap rowspan="2">Sales Executive</th>
                                                        <th nowrap colspan="5" class="text-center">2KG</th>
                                                        <th nowrap colspan="5" class="text-center">5KG</th>
                                                        <th nowrap colspan="5" class="text-center">12.5KG</th>
                                                        <th nowrap colspan="5" class="text-center">37.5KG</th>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th colspan="5" class="text-center"><?php echo $rowproductaccesories; ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">   
                                <div class="card shadow-none">
                                    <div class="card-body">                        
                                        <h6 class="small title-style"><span>Driver target on <?php echo date('F Y') ?></span></h6>
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-striped table-bordered table-sm small" id="driverTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th nowrap rowspan="2">Driver</th>
                                                        <th nowrap colspan="5" class="text-center">2KG</th>
                                                        <th nowrap colspan="5" class="text-center">5KG</th>
                                                        <th nowrap colspan="5" class="text-center">12.5KG</th>
                                                        <th nowrap colspan="5" class="text-center">37.5KG</th>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th colspan="5" class="text-center"><?php echo $rowproductaccesories; ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <?php foreach($productaccesorieslist as $rowproductaccesories){ ?>
                                                        <th nowrap class="text-center">Target</th>
                                                        <th nowrap class="text-center">Completed</th>
                                                        <th nowrap class="text-center">Balance</th>
                                                        <th nowrap class="text-center">Balance %</th>
                                                        <th nowrap class="text-center">Balance Average/Day</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Discount customer sales on <?php echo date('Y-m-d') ?></span></h6>
                                        <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Invoice</th>
                                                    <th>Date</th>
                                                    <th>Customer</th>
                                                    <th>Executive Name</th>
                                                    <th>Area</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">Balance</th>
                                                    <th>Payment</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Buffer maintenance on <?php echo date('Y-m-d') ?></span></h6>
                                        <div class="scrollbar pb-3" id="style-2">
                                            <table class="table table-bordered table-striped table-sm nowrap" id="dataTablebuffer">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th class="align-top" rowspan="3">Customer</th>
                                                        <th class="align-top" rowspan="3">Executive</th>
                                                        <th class="align-top" rowspan="3">Area</th>
                                                        <th class="text-center" colspan="4">2KG</th>
                                                        <th class="text-center" colspan="4">5KG</th>
                                                        <th class="text-center" colspan="4">12.5KG</th>
                                                        <th class="text-center" colspan="4">37.5KG</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" colspan="2">Full</th>
                                                        <th class="text-center" colspan="2">Empty</th>
                                                        <th class="text-center" colspan="2">Full</th>
                                                        <th class="text-center" colspan="2">Empty</th>
                                                        <th class="text-center" colspan="2">Full</th>
                                                        <th class="text-center" colspan="2">Empty</th>
                                                        <th class="text-center" colspan="2">Full</th>
                                                        <th class="text-center" colspan="2">Empty</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                        <th class="text-center">Req</th>
                                                        <th class="text-center">Ava</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
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
<!-- Modal Invoice Receipt -->
<div class="modal fade" id="modalinvoicereceipt" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function(){
        vehicleavailablestock();
        stocksummery();
        loadDraft();
        employeetarget('7', 'executiveTable');
        employeetarget('4', 'driverTable');
        upcomingBirthdays();
        buffermaintenance();

        setInterval (vehicleavailablestock, 300000);
        setInterval (stocksummery, 300000);
        setInterval (buffermaintenance, 300000);
        setInterval(function() {
            employeetarget('7', 'executiveTable');
        }, 300000);
        setInterval(function() {
            employeetarget('4', 'driverTable');
        }, 300000);

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/dashboardinvoiceviewlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        var invoiceNumber;
                        if (full['tax_invoice_num'] == '') {
                            invoiceNumber = 'INV-' + full['idtbl_invoice'];
                        }else {
                            invoiceNumber = 'AGT' + full['tax_invoice_num'];
                        }
                        return invoiceNumber;
                    }
                },
                { 
                    "data": "date" 
                },
                { 
                    "data": "cusname" 
                },
                { 
                    "data": "repname" 
                },
                { 
                    "data": "area" 
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['nettotal']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var balance = parseFloat(full['balance_amount']);
                        var fixedBalance = balance.toFixed(2);
                        if (balance < 0) {
                            return '0.00';
                        } else {
                            return addCommas(fixedBalance);
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['paymentcomplete']==1) {
                            return 'Complete';
                        } else {
                            return 'Pending';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-outline-dark btn-sm btnView mr-1" id="' + full['idtbl_invoice'] + '"><i class="fas fa-eye"></i></button>';
                        return button;
                    }
                }
            ],
            "rowCallback": function(row, data) {
                // Highlight the entire row with background danger if status is 3
                if (data.status == 3) {
                    $(row).addClass('bg-danger text-white');
                }
            }
        });
        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');

            $('#modalinvoicereceipt').modal('show');
            $('#viewreceiptprint').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getinvoiceprint.php',
                success: function(result) { //alert(result);
                    $('#viewreceiptprint').html(result);
                }
            });
        });
    });

    function vehicleavailablestock(){ //alert('IN');
        Swal.fire({
            title: '',
            html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false, // Hide the OK button
            backdrop: `
                rgba(255, 255, 255, 0.5) 
            `,
            customClass: {
                popup: 'fullscreen-swal'
            },
            didOpen: () => {
                document.body.style.overflow = 'hidden';

                $.ajax({
                    type: "POST",
                    data: {},
                    url: 'getprocess/getdashboardvehicleavailablestock.php',
                    success: function(result) {
                        Swal.close();
                        $('#loadstocktable > tbody').html(result);
                        if (isCanvasEmpty($('#salechart')[0])) {
                            $('#waitload').html('<div class="div-spinner"><div class="custom-loader"></div></div>');
                        }
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });

                document.body.style.overflow = 'visible';
            }
        });
    }
    function stocksummery(){ //alert('IN');
        Swal.fire({
            title: '',
            html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false, // Hide the OK button
            backdrop: `
                rgba(255, 255, 255, 0.5) 
            `,
            customClass: {
                popup: 'fullscreen-swal'
            },
            didOpen: () => {
                document.body.style.overflow = 'hidden';

                $.ajax({
                    type: "POST",
                    data: {},
                    url: 'getprocess/getdashboardstocksummery.php',
                    success: function(result) {
                        Swal.close();
                        $('#mainstocksummerytable > tbody').html(result);
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });

                document.body.style.overflow = 'visible';
            }
        });
    }
    function loadDraft(){
        $.ajax({
            type: "POST",
            data: {},
            url: 'getprocess/getdashboarddraft.php',
            success: function(result) {   
                $('#waitload').html('');             
                var obj = JSON.parse(result);
                
                var productone = obj.productonearray;
                var producttwo = obj.producttwoarray;
                var productthree = obj.productthreearray;
                var productfour = obj.productfourarray;
                var xline = obj.daysarray;
                
                // var ctx = document.getElementById('salechart').getContext('2d');
                // var chart = new Chart(ctx, {
                //     // The type of chart we want to create
                //     type: 'bar',

                //     // The data for our dataset
                //     data: {
                //         labels: xline,
                //         datasets: [{
                //             label: '12.5 KG Filled',
                //             backgroundColor: '#00ac69',
                //             borderColor: '#00ac69',
                //             borderWidth: 1,
                //             data: productone
                //         }, {
                //             label: '37.5 KG Filled',
                //             backgroundColor: '#e81500',
                //             borderColor: '#e81500',
                //             borderWidth: 1,
                //             data: producttwo
                //         }, {
                //             label: '5 KG Filled',
                //             backgroundColor: '#1f2d41',
                //             borderColor: '#1f2d41',
                //             borderWidth: 1,
                //             data: productthree
                //         }, {
                //             label: '2 KG Filled',
                //             backgroundColor: '#0061f2',
                //             borderColor: '#0061f2',
                //             borderWidth: 1,
                //             data: productfour
                //         }]
                //     },

                //     // Configuration options go here
                //     options: {
                //         responsive: true,
                //         scales: {
                //             y: {
                //                 beginAtZero: true,
                //                 title: {
                //                     display: true,
                //                     text: 'Product sale'
                //                 }
                //             }
                //         }
                //     }
                // });
                var ctx = document.getElementById('salechart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: xline,
                        datasets: [{
                            label: '2 KG Filled (' + obj.total2kg + ')',
                            backgroundColor: '#0061f2',
                            borderColor: '#0061f2',
                            borderWidth: 1,
                            data: productfour
                        }, {
                            label: '5 KG Filled (' + obj.total5kg + ')',
                            backgroundColor: '#1f2d41',
                            borderColor: '#1f2d41',
                            borderWidth: 1,
                            data: productthree
                        }, 
                        {
                            label: '12.5 KG Filled (' + obj.total12_5kg + ')',
                            backgroundColor: '#00ac69',
                            borderColor: '#00ac69',
                            borderWidth: 1,
                            data: productone
                        }, {
                            label: '37.5 KG Filled (' + obj.total37_5kg + ')',
                            backgroundColor: '#e81500',
                            borderColor: '#e81500',
                            borderWidth: 1,
                            data: producttwo
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12, // Increase y-axis tick font size
                                    // fontStyle: 'bold' // Optional: Make it bold
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Product Sale',
                                    fontSize: 16, // Increase y-axis title font size
                                    fontStyle: 'bold' // Optional: Make it bold
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontSize: 12, // Increase x-axis tick font size
                                    // fontStyle: 'bold' // Optional: Make it bold
                                }
                            }]
                        },
                        legend: {
                            labels: {
                                fontSize: 12, // Increase legend font size
                                fontStyle: 'bold' // Optional: Make it bold
                            }
                        }
                    }
                });
            },
            error: function(error) {
                // Close the SweetAlert on error
                Swal.close();
                
                // Show an error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again later.'
                });
            }
        });
    }
    function employeetarget(empType, tableID){ //alert('IN');
        $.ajax({
            type: "POST",
            data: {
                empType: empType
            },
            url: 'getprocess/getdashboardmonthlyemployeetarget.php',
            success: function(result) {//alert(result);
                $('#' + tableID + ' > tbody').html(result);
            }
        });
    }
    function upcomingBirthdays(){ //alert('IN');
        $.ajax({
            type: "POST",
            data: {
                bdaytype: '1'
            },
            url: 'getprocess/getdashboardbirthdays.php',
            success: function(result) {//alert(result);
                $('#bdayTable > tbody').html(result);
            }
        });
    }
    function buffermaintenance(){ //alert('IN');
        Swal.fire({
            title: '',
            html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false, // Hide the OK button
            backdrop: `
                rgba(255, 255, 255, 0.5) 
            `,
            customClass: {
                popup: 'fullscreen-swal'
            },
            didOpen: () => {
                document.body.style.overflow = 'hidden';

                $.ajax({
                    type: "POST",
                    data: {},
                    url: 'getprocess/getdashboardbuffermaintenance.php',
                    success: function(result) {
                        Swal.close();
                        $('#dataTablebuffer > tbody').html(result);
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });

                document.body.style.overflow = 'visible';
            }
        });
    }

    function isCanvasEmpty(canvas) {
        const context = canvas.getContext('2d');
        const pixelBuffer = new Uint32Array(
            context.getImageData(0, 0, canvas.width, canvas.height).data.buffer
        );

        return !pixelBuffer.some(color => color !== 0);
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
