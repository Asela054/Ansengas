<?php 
include "include/header.php"; 

// $thismonth = date("n"); 
// $thisyear = date("Y"); 

//Sale Chart
// $daysarray=array();
// $productonearray=array();
// $producttwoarray=array();
// $productthreearray=array();
// $productfourarray=array();
// $dayscount=cal_days_in_month(CAL_GREGORIAN,$thismonth,$thisyear);

// for($i=1; $i<=$dayscount; $i++){
//     $daysarray[]=$i;

//     if($i<=date('d')){
//         $arrayproduct=array('1','2','4','6');
//         foreach($arrayproduct as $rowproduct){
//             $newsalecount=0;
//             $refillsalecount=0;

//             $invdate=date('Y-m-').$i;
//             $sqlsaleproduct="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `date`='$invdate' AND `status`=1) AND `status`=1 AND `tbl_product_idtbl_product`='$rowproduct'";
//             $resultsaleproduct=$conn->query($sqlsaleproduct);
//             $rowsaleproduct=$resultsaleproduct->fetch_assoc();

//             if(!empty($rowsaleproduct['newqty'])){
//                 $newsalecount=$rowsaleproduct['newqty'];
//             }
//             if(!empty($rowsaleproduct['refillqty'])){
//                 $refillsalecount=$rowsaleproduct['refillqty'];
//             }

//             $totsalecount=$newsalecount+$refillsalecount;

//             if($rowproduct==1){
//                 $productonearray[]=$totsalecount;
//             }
//             else if($rowproduct==2){
//                 $producttwoarray[]=$totsalecount;
//             }
//             else if($rowproduct==4){
//                 $productthreearray[]=$totsalecount;
//             }
//             else if($rowproduct==6){
//                 $productfourarray[]=$totsalecount;
//             }
//         }
//     }
// }

//Stock Information
// $sqlfullstock="SELECT SUM(`fullqty`) AS `fullqty` FROM `tbl_stock` WHERE `status`=1 AND `tbl_product_idtbl_product` IN (SELECT `idtbl_product` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1)";
// $resultfullstock=$conn->query($sqlfullstock);
// $rowfullstock=$resultfullstock->fetch_assoc();

// //Total Outstanding
// $sqlinvoicetot="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `status`=1";
// $resultinvoicetot=$conn->query($sqlinvoicetot);
// $rowinvoicetot=$resultinvoicetot->fetch_assoc();

// $sqlpaytot="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice`";
// $resultpaytot=$conn->query($sqlpaytot);
// $rowpaytot=$resultpaytot->fetch_assoc();

// if($rowinvoicetot['total']==''){$invtot=0;}
// else{$invtot=$rowinvoicetot['total'];}

// if($rowpaytot['payamount']==''){$invpaytot=0;}
// else{$invpaytot=$rowpaytot['payamount'];}

// $outstanding=$invtot-$invpaytot;

// $targettotal=0;
// $targettotalref=0;
// $targettotalvehi=0;

// $targetarray=array();
// $arrayproduct=array('1','2','4','6');
// foreach($arrayproduct as $rowproduct){
//     $sqltarget="SELECT `tbl_product`.`product_name`, SUM(`tbl_vehicle_target`.`targettank`) AS `vehicletarget`, SUM(`tbl_employee_target`.`targettank`) AS `reftarget`, SUM(`tbl_vehicle_target`.`targetcomplete`) AS `vehiclecomplete`, SUM(`tbl_employee_target`.`targetcomplete`) AS `refcomplete` FROM `tbl_product` LEFT JOIN `tbl_vehicle_target` ON `tbl_vehicle_target`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` LEFT JOIN `tbl_employee_target` ON `tbl_employee_target`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` WHERE `tbl_product`.`idtbl_product`='$rowproduct' AND `tbl_product`.`status`=1 AND `tbl_vehicle_target`.`status`=1 AND `tbl_employee_target`.`status`=1 AND MONTH(`tbl_employee_target`.`month`)='$thismonth' AND MONTH(`tbl_vehicle_target`.`month`)='$thismonth'";
//     $resulttarget=$conn->query($sqltarget);
//     $rowtarget=$resulttarget->fetch_assoc();

//     $target=($rowtarget['vehicletarget']+$rowtarget['reftarget']);
//     $complete=($rowtarget['vehiclecomplete']+$rowtarget['refcomplete']);
//     $balance=$target-$complete;
//     $balancedays=$dayscount-date('d');

//     $targettotal=$targettotal+$target;
//     $targettotalref=$targettotalref+$rowtarget['refcomplete'];
//     $targettotalvehi=$targettotalvehi+$rowtarget['vehiclecomplete'];

//     if($balancedays==0){
//         $avgqtytarget=round($balance);
//     }
//     else{
//         $avgqtytarget=round($balance/$balancedays);
//     }

//     $obj=new stdClass();
//     $obj->product=$rowtarget['product_name'];
//     $obj->target=$target;
//     $obj->complete=$complete;
//     $obj->average=$avgqtytarget;
    
//     array_push($targetarray, $obj);
// }

// //Retail Outstanding
// $sqlretailout="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `paymentcomplete`=0 AND `status`=1 AND `tbl_customer_idtbl_customer` IN (SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type`='2' AND `status`=1)";
// $resultretailout=$conn->query($sqlretailout);
// $rowretailout=$resultretailout->fetch_assoc();

// //Co-operate Outstanding
// $sqlcooperateout="SELECT SUM(`tbl_invoice`.`total`) AS `total`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer` IN (SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type`='1' AND `status`=1)";
// $resultcooperateout=$conn->query($sqlcooperateout);
// $rowcooperateout=$resultcooperateout->fetch_assoc();

// //Chart Target
// $monthchartarray=array();
// $targetchartarray=array();
// $completechartarray=array();

// for($j=1; $j<=12; $j++){
//     $month=$j;

//     $dateObj   = DateTime::createFromFormat('!m', $month);
//     $monthName = $dateObj->format('F');

//     $monthchartarray[]=$monthName;
    
//     if($month<=$thismonth){
//         $sqltargetchart="SELECT SUM(`tbl_vehicle_target`.`targettank`) AS `vehicletarget`, SUM(`tbl_employee_target`.`targettank`) AS `reftarget`, SUM(`tbl_vehicle_target`.`targetcomplete`) AS `vehiclecomplete`, SUM(`tbl_employee_target`.`targetcomplete`) AS `refcomplete` FROM `tbl_vehicle_target` LEFT JOIN `tbl_employee_target` ON `tbl_employee_target`.`tbl_product_idtbl_product`=`tbl_vehicle_target`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_target`.`status`=1 AND `tbl_employee_target`.`status`=1 AND MONTH(`tbl_employee_target`.`month`)='$month' AND MONTH(`tbl_vehicle_target`.`month`)='$month'";
//         $resulttargetchart=$conn->query($sqltargetchart);
//         $rowtargetchart=$resulttargetchart->fetch_assoc();

//         $targetchartarray[]=($rowtargetchart['vehicletarget']+$rowtargetchart['reftarget']);
//         $completechartarray[]=($rowtargetchart['vehiclecomplete']+$rowtargetchart['refcomplete']);
//     }
// }

//Vehicle available stock


include "include/topnavbar.php"; 
?>
<style>
.watermarked-card {
    position: relative;
}

.watermarked-card::before {
    content: "";
    position: absolute;
    top: 5rem;
    left: 30rem;
    width: 35rem;
    height: 35rem;
    background-image: url('images/logo.png');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center center;
    opacity: 0.1;
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
                    <div class="card-body watermarked-card" style="height:45rem;">
                        <!-- <div class="row">
                            <div class="col-5">
                                <div class="row row-cols-1 row-cols-md-2">
                                    <a href="stock.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-primary card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-primary">
                                                        <i class="flaticon-028-shelf"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h1 class=" text-primary my-1"><?php echo number_format($rowfullstock['fullqty']); ?></h1>
                                                            <h6 class="card-title text-primary m-0 small">Full Stock</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row no-gutters h-100">
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <div class="progress" style="height: 3px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo ($rowfullstock['fullqty']/5000)*100; ?>%;" aria-valuenow="<?php echo ($rowfullstock['fullqty']/5000)*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="col mb-3">
                                        <div class="card shadow-none border-success card-icon p-0">
                                            <div class="row no-gutters h-100">
                                                <div class="col-auto card-icon-aside-new text-success">
                                                    <i class="flaticon-005-calendar"></i>
                                                </div>
                                                <div class="col">
                                                    <div class="card-body p-0 p-2 text-right">
                                                        <h1 class=" text-success my-1"><?php echo number_format($targettotal) ?></h1>
                                                        <h6 class="card-title text-success m-0 small">Target this month</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row no-gutters h-100">
                                                <div class="col">
                                                    <div class="card-body p-0 p-2 text-right">
                                                        <div class="progress" style="height: 3px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo ($targettotal/$rowfullstock['fullqty'])*100; ?>%;" aria-valuenow="<?php echo ($targettotal/$rowfullstock['fullqty'])*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="employeetarget.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-danger card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-danger">
                                                        <i class="flaticon-032-stopwatch"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h1 class=" text-danger my-1"><?php echo number_format($targettotalref) ?></h1>
                                                            <h6 class="card-title text-danger m-0 small">Sale's ref target</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row no-gutters h-100">
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <div class="progress" style="height: 3px;">
                                                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo ($targettotalref/$targettotal)*100; ?>%;" aria-valuenow="<?php echo ($targettotalref/$targettotal)*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="vehicletarget.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-dark card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-dark">
                                                        <i class="flaticon-036-truck"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h1 class=" text-dark my-1"><?php echo number_format($targettotalvehi) ?></h1>
                                                            <h6 class="card-title text-dark m-0 small">Vehicle target</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row no-gutters h-100">
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <div class="progress" style="height: 3px;">
                                                                <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo ($targettotalvehi/$targettotal)*100; ?>%;" aria-valuenow="<?php echo ($targettotalvehi/$targettotal)*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="customeroutstanding.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-purple card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-purple">
                                                        <i class="flaticon-039-warehouse"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h3 class=" text-purple my-1">Rs. <?php echo number_format($outstanding, 2) ?></h3>
                                                            <h6 class="card-title text-purple m-0 small">Total Outstanding</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="customeroutstanding.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-pink card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-pink">
                                                        <i class="flaticon-040-warehouse"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h3 class=" text-pink my-1">Rs. <?php echo number_format(($rowcooperateout['total']-$rowcooperateout['payamount']), 2) ?></h3>
                                                            <h6 class="card-title text-pink m-0 small">Co-op Outstanding</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="customeroutstanding.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-teal card-icon p-0">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-teal">
                                                        <i class="flaticon-045-warehouse"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h3 class=" text-teal my-1">Rs. <?php echo number_format($rowretailout['total'], 2) ?></h3>
                                                            <h6 class="card-title text-teal m-0 small">Retail Outstanding</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="card border-black shadow-none">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Monthly Sale Summary</span></h6>
                                        <canvas id="salechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-5">
                                <h6 class="small title-style"><span>Target Achivement</span></h6>
                                <table class="table table-striped table-bordered table-sm small">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Target</th>
                                            <th class="text-center">Complete</th>
                                            <th class="text-center">Average</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($targetarray as $rowtargetavg){ ?>
                                        <tr>
                                            <td><?php echo $rowtargetavg->product ?></td>
                                            <td class="text-center"><?php echo $rowtargetavg->target ?></td>
                                            <td class="text-center"><?php echo $rowtargetavg->complete ?></td>
                                            <td class="text-center"><?php echo $rowtargetavg->average ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <h6 class="small title-style"><span>Vehicle Available</span></h6>
                                <div id="viewavailablevehicle"></div>
                                <h6 class="small title-style"><span>Employee Target</span></h6>
                                <div id="viewemployeetarget"></div>
                                <h6 class="small title-style"><span>Vehicle Target</span></h6>
                                <div id="viewvehicletarget"></div>
                            </div>
                            <div class="col-7">
                                <div class="card border-black shadow-none">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Target Summary</span></h6>
                                        <canvas id="targetchart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function(){
        var productone = <?php echo json_encode($productonearray); ?>;
        var producttwo = <?php echo json_encode($producttwoarray); ?>;
        var productthree = <?php echo json_encode($productthreearray); ?>;
        var productfour = <?php echo json_encode($productfourarray); ?>;
        var xline = <?php echo json_encode($daysarray); ?>;

        var ctx = document.getElementById('salechart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: xline,
                datasets: [{
                    label: '12.5 KG Filled',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#00ac69',
                    borderWidth: 1,
                    data: productone
                }, {
                    label: '37.5 KG Filled',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#e81500',
                    borderWidth: 1,
                    data: producttwo
                }, {
                    label: '5 KG Filled',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#1f2d41',
                    borderWidth: 1,
                    data: productthree
                }, {
                    label: '2 KG Filled',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#0061f2',
                    borderWidth: 1,
                    data: productfour
                }]
            },

            // Configuration options go here
            options: {
                scales: {
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Product sale'
                        },
                        ticks: {
                            min: 0,
                        }
                    }]
                },
                responsive: true
            }
        });

        var xtarline = <?php echo json_encode($monthchartarray); ?>;
        var tartotal = <?php echo json_encode($targetchartarray); ?>;
        var completetotal = <?php echo json_encode($completechartarray); ?>;

        var ctx = document.getElementById('targetchart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: xtarline,
                datasets: [{
                    label: 'Target Tanks',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#00ac69',
                    borderWidth: 1,
                    data: tartotal
                }, {
                    label: 'Complete Tanks',
                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                    borderColor: '#e81500',
                    borderWidth: 1,
                    data: completetotal
                }]
            },

            // Configuration options go here
            options: {
                scales: {
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Product sale'
                        },
                        ticks: {
                            min: 0,
                        }
                    }]
                },
                responsive: true
            }
        });

        vehicleavailablestock();
        vehicletarget();
        employeetarget();

        setInterval ( vehicleavailablestock, 180000 );
    });

    function vehicleavailablestock(){ //alert('IN');
        $.ajax({
            type: "POST",
            data: {},
            url: 'getprocess/getvehicleavailablestock.php',
            success: function(result) {//alert(result);
                $('#viewavailablevehicle').html(result);
            }
        });
    }

    function employeetarget(){ //alert('IN');
        $.ajax({
            type: "POST",
            data: {},
            url: 'getprocess/getmonthlyemployeetarget.php',
            success: function(result) {//alert(result);
                $('#viewemployeetarget').html(result);
            }
        });
    }

    function vehicletarget(){ //alert('IN');
        $.ajax({
            type: "POST",
            data: {},
            url: 'getprocess/getmonthlyvehicletarget.php',
            success: function(result) {//alert(result);
                $('#viewvehicletarget').html(result);
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
