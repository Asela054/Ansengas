<?php 
include "include/header.php"; 

$thismonth = date("n"); 
$thisyear = date("Y"); 

$daysarray = array();
$productonearray = array();
$producttwoarray = array();
$productthreearray = array();
$productfourarray = array();
$dayscount = cal_days_in_month(CAL_GREGORIAN, $thismonth, $thisyear);

for ($i = 1; $i <= $dayscount; $i++) {
    $daysarray[] = $i;

    if ($i <= date('d')) {
        $arrayproduct = array('1', '2', '4', '6');
        foreach ($arrayproduct as $rowproduct) {
            $newsalecount = 0;
            $refillsalecount = 0;

            $invdate = date('Y-m-') . $i;
            $sqlsaleproduct = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `date`='$invdate' AND `status`=1) AND `status`=1 AND `tbl_product_idtbl_product`='$rowproduct'";
            $resultsaleproduct = $conn->query($sqlsaleproduct);
            $rowsaleproduct = $resultsaleproduct->fetch_assoc();

            if (!empty($rowsaleproduct['newqty'])) {
                $newsalecount = $rowsaleproduct['newqty'];
            }
            if (!empty($rowsaleproduct['refillqty'])) {
                $refillsalecount = $rowsaleproduct['refillqty'];
            }

            $totsalecount = $newsalecount + $refillsalecount;

            if ($totsalecount > 0) {
                if ($rowproduct == 1) {
                    $productonearray[] = $totsalecount;
                } else if ($rowproduct == 2) {
                    $producttwoarray[] = $totsalecount;
                } else if ($rowproduct == 4) {
                    $productthreearray[] = $totsalecount;
                } else if ($rowproduct == 6) {
                    $productfourarray[] = $totsalecount;
                }
            } else {
                if ($rowproduct == 1) {
                    $productonearray[] = null;
                } else if ($rowproduct == 2) {
                    $producttwoarray[] = null;
                } else if ($rowproduct == 4) {
                    $productthreearray[] = null;
                } else if ($rowproduct == 6) {
                    $productfourarray[] = null;
                }
            }
        }
    }
}


//Stock Information
 $sqlfullstock="SELECT SUM(`fullqty`) AS `fullqty` FROM `tbl_stock` WHERE `status`=1 AND `tbl_product_idtbl_product`  IN (SELECT `idtbl_product` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1)";
 $resultfullstock=$conn->query($sqlfullstock);
 $rowfullstock=$resultfullstock->fetch_assoc();

//Total Outstanding
$sqlinvoicetot="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `status`=1";
$resultinvoicetot=$conn->query($sqlinvoicetot);
$rowinvoicetot=$resultinvoicetot->fetch_assoc();

$sqlpaytot="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice`";
$resultpaytot=$conn->query($sqlpaytot);
$rowpaytot=$resultpaytot->fetch_assoc();

if($rowinvoicetot['total']==''){$invtot=0;}
else{$invtot=$rowinvoicetot['total'];}

if($rowpaytot['payamount']==''){$invpaytot=0;}
else{$invpaytot=$rowpaytot['payamount'];}

$outstanding=$invtot-$invpaytot;

$targettotal=0;
$targettotalref=0;
$targettotalvehi=0;

$targetarray=array();
$arrayproduct = array();

$sql = "SELECT `idtbl_product` FROM `tbl_product` WHERE `status` = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arrayproduct[] = $row['idtbl_product'];
    }
}
foreach($arrayproduct as $rowproduct){
    $sqltarget="SELECT `tbl_product`.`product_name`, SUM(`tbl_vehicle_target`.`targettank`) AS `vehicletarget`, SUM(`tbl_employee_target`.`targettank`) AS `reftarget`, SUM(`tbl_vehicle_target`.`targetcomplete`) AS `vehiclecomplete`, SUM(`tbl_employee_target`.`targetcomplete`) AS `refcomplete` FROM `tbl_product` LEFT JOIN `tbl_vehicle_target` ON `tbl_vehicle_target`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` LEFT JOIN `tbl_employee_target` ON `tbl_employee_target`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` WHERE `tbl_product`.`idtbl_product`='$rowproduct' AND `tbl_product`.`status`=1 AND `tbl_vehicle_target`.`status`=1 AND `tbl_employee_target`.`status`=1 AND MONTH(`tbl_employee_target`.`month`)='$thismonth' AND MONTH(`tbl_vehicle_target`.`month`)='$thismonth'";
    $resulttarget=$conn->query($sqltarget);
    $rowtarget=$resulttarget->fetch_assoc();

    $target=($rowtarget['vehicletarget']+$rowtarget['reftarget']);
    $complete=($rowtarget['vehiclecomplete']+$rowtarget['refcomplete']);
    $balance=$target-$complete;
    $balancedays=$dayscount-date('d');

    $targettotal=$targettotal+$target;
    $targettotalref=$targettotalref+$rowtarget['refcomplete'];
    $targettotalvehi=$targettotalvehi+$rowtarget['vehiclecomplete'];

    if($balancedays==0){
        $avgqtytarget=round($balance);
    }
    else{
        $avgqtytarget=round($balance/$balancedays);
    }

    $obj=new stdClass();
    $obj->product=$rowtarget['product_name'];
    $obj->target=$target;
    $obj->complete=$complete;
    $obj->average=$avgqtytarget;
    
    array_push($targetarray, $obj);
}


//Gass accessories
$qtyarray = array();
foreach ($arrayproduct as $rowproduct) {
    $sqlqty = "SELECT p.product_name, p.orderlevel, s.fullqty, s.emptyqty 
               FROM tbl_stock s 
               INNER JOIN tbl_product p ON s.tbl_product_idtbl_product = p.idtbl_product 
               WHERE s.status = 1 AND s.tbl_product_idtbl_product = '$rowproduct'";
    $resultqty = $conn->query($sqlqty);
    $rowqty = $resultqty->fetch_assoc();

    $obj = new stdClass();
    $obj->gass = $rowqty['product_name']; 
    $obj->orderlevel = $rowqty['orderlevel']; // Store orderlevel
    $obj->fullqty = $rowqty['fullqty']; 
    $obj->emptyqty = $rowqty['emptyqty'];
    
    array_push($qtyarray, $obj);
}

// Sort $qtyarray by orderlevel
usort($qtyarray, function($a, $b) {
    return $a->orderlevel - $b->orderlevel;
});
   
//Employee Target
$sql = "SELECT 
    e.name AS employee_name, 
    MONTHNAME(CURDATE()) AS month_name,
    p.product_name AS product,
    et.targettank AS target,
    et.targetcomplete AS completed,
    DAY(LAST_DAY(CURDATE())) AS total_days_in_month,
    (DAY(LAST_DAY(CURDATE())) - COALESCE(hm.total_holidays_in_month, 0)) AS working_days_in_month,
    GREATEST(DAY(CURDATE()) - COALESCE(hu.holidays_until_today, 0), 1) AS working_days_completed,
    GREATEST(
        (DAY(LAST_DAY(CURDATE())) - COALESCE(hm.total_holidays_in_month, 0)) 
        - (DAY(CURDATE()) - COALESCE(hu.holidays_until_today, 0)), 
        0
    ) AS balance_days,
    -- Completed Target Achievement % (Capped at 100)
    LEAST(
        ROUND(
            (et.targetcomplete / NULLIF(GREATEST(DAY(CURDATE()) - COALESCE(hu.holidays_until_today, 0), 1), 0)) 
            / (et.targettank / NULLIF((DAY(LAST_DAY(CURDATE())) - COALESCE(hm.total_holidays_in_month, 0)), 0)) 
            * 100, 2
        ), 
        100
    ) AS completed_days_target_achievement_pct,
    -- New Column: Pending Target Achievement %
    GREATEST(
        100 - LEAST(
            ROUND(
                (et.targetcomplete / NULLIF(GREATEST(DAY(CURDATE()) - COALESCE(hu.holidays_until_today, 0), 1), 0)) 
                / (et.targettank / NULLIF((DAY(LAST_DAY(CURDATE())) - COALESCE(hm.total_holidays_in_month, 0)), 0)) 
                * 100, 2
            ), 
            100
        ), 
        0
    ) AS pending_target_achievement_pct
FROM 
    tbl_employee_target et
INNER JOIN 
    tbl_employee e ON et.tbl_employee_idtbl_employee = e.idtbl_employee
INNER JOIN 
    tbl_product p ON et.tbl_product_idtbl_product = p.idtbl_product
LEFT JOIN 
    (SELECT MONTH(date) AS month, COUNT(*) AS total_holidays_in_month
     FROM tbl_month_holiday
     WHERE MONTH(date) = MONTH(CURDATE())
     GROUP BY MONTH(date)) AS hm ON MONTH(CURDATE()) = hm.month
LEFT JOIN 
    (SELECT MONTH(date) AS month, COUNT(*) AS holidays_until_today
     FROM tbl_month_holiday
     WHERE MONTH(date) = MONTH(CURDATE()) AND date <= CURDATE()
     GROUP BY MONTH(date)) AS hu ON MONTH(CURDATE()) = hu.month
WHERE 
    e.tbl_user_type_idtbl_user_type = 7
GROUP BY 
    e.idtbl_employee, p.idtbl_product
";
$result = $conn->query($sql);





//Retail Outstanding
$sqlretailout="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `paymentcomplete`=0 AND `status`=1 AND `tbl_customer_idtbl_customer` IN (SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type`='2' AND `status`=1)";
$resultretailout=$conn->query($sqlretailout);
$rowretailout=$resultretailout->fetch_assoc();

//Co-operate Outstanding
$sqlcooperateout="SELECT SUM(`tbl_invoice`.`total`) AS `total`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer` IN (SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type`='1' AND `status`=1)";
$resultcooperateout=$conn->query($sqlcooperateout);
$rowcooperateout=$resultcooperateout->fetch_assoc();

//Chart Target
$monthchartarray=array();
$targetchartarray=array();
$completechartarray=array();

for($j=1; $j<=12; $j++){
    $month=$j;

    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');

    $monthchartarray[]=$monthName;
    
    if($month<=$thismonth){
        $sqltargetchart="SELECT SUM(`tbl_vehicle_target`.`targettank`) AS `vehicletarget`, SUM(`tbl_employee_target`.`targettank`) AS `reftarget`, SUM(`tbl_vehicle_target`.`targetcomplete`) AS `vehiclecomplete`, SUM(`tbl_employee_target`.`targetcomplete`) AS `refcomplete` FROM `tbl_vehicle_target` LEFT JOIN `tbl_employee_target` ON `tbl_employee_target`.`tbl_product_idtbl_product`=`tbl_vehicle_target`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_target`.`status`=1 AND `tbl_employee_target`.`status`=1 AND MONTH(`tbl_employee_target`.`month`)='$month' AND MONTH(`tbl_vehicle_target`.`month`)='$month'";
        $resulttargetchart=$conn->query($sqltargetchart);
        $rowtargetchart=$resulttargetchart->fetch_assoc();

        $targetchartarray[]=($rowtargetchart['vehicletarget']+$rowtargetchart['reftarget']);
        $completechartarray[]=($rowtargetchart['vehiclecomplete']+$rowtargetchart['refcomplete']);
    }
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
                                <div class="card border-black shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Monthly Sale Summary</span></h6>
                                        <canvas id="salechart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-black shadow-none h-100">
                                    <div class="card-body">
                                        <h6 class="small title-style"><span>Stock Reports</span></h6>
                                        <table class="table table-striped table-bordered table-sm small">
                                            <thead class="bg-warning-soft">
                                                <tr>
                                                    <th>Gas Accesaries</th>
                                                    <th class="text-center">Full Qty</th>
                                                    <th class="text-center">Empty Qty</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($qtyarray as $rowqty) { ?>
                                                <tr>
                                                    <td><?php echo $rowqty->gass; ?></td>
                                                    <td class="text-center"><?php echo $rowqty->fullqty; ?></td>
                                                    <td class="text-center"><?php echo $rowqty->emptyqty; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- <div class="col">
                                    <div class="card border-black shadow-none">
                                        <div class="card-body">
                                            <h6 class="small title-style"><span>Target Summary</span></h6>
                                            <canvas id="targetchart"></canvas>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <!-- <div class="row mt-2">
                            <div class="col-12">                           
                                <h6 class="small title-style"><span>Employee Target</span></h6>
                                <table class="table table-striped table-bordered table-sm small">
                                <thead class="bg-danger-soft">
                                    <tr>
                                        <th>Sales Executive</th>
                                        <th>Month</th>
                                        <th>Product</th>
                                        <th>Target</th>
                                        <th>Completed</th>
                                        <th>Total Working Days</th>
                                        <th>Completed Days</th>
                                        <th>Balance Days</th>
                                        <th>Average Target/Day</th>
                                        <th>Balance Average/Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($rowinfo = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$rowinfo['employee_name']}</td>
                                                <td>{$rowinfo['month_name']}</td>
                                                <td>{$rowinfo['product']}</td>
                                                <td>{$rowinfo['target']}</td>
                                                <td>{$rowinfo['completed']}</td>
                                                <td>{$rowinfo['total_days_in_month']}</td>
                                                <td>{$rowinfo['working_days_completed']}</td>
                                                <td>{$rowinfo['balance_days']}</td>
                                                <td>{$rowinfo['completed_days_target_achievement_pct']}</td>
                                                <td>{$rowinfo['pending_target_achievement_pct']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                            

                            <div class="col-12">
                                <h6 class="small title-style"><span>Vehicle Available</span></h6>
                                <div id="viewavailablevehicle"></div>
                                <h6 class="small title-style"><span>Employee Target</span></h6>
                                <div id="viewemployeetarget"></div>
                                <h6 class="small title-style"><span>Vehicle Target</span></h6>
                                <div id="viewvehicletarget"></div>
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
            type: 'bar',

            // The data for our dataset
            data: {
                labels: xline,
                datasets: [{
                    label: '12.5 KG Filled',
                    backgroundColor: '#00ac69',
                    borderColor: '#00ac69',
                    borderWidth: 1,
                    data: productone
                }, {
                    label: '37.5 KG Filled',
                    backgroundColor: '#e81500',
                    borderColor: '#e81500',
                    borderWidth: 1,
                    data: producttwo
                }, {
                    label: '5 KG Filled',
                    backgroundColor: '#1f2d41',
                    borderColor: '#1f2d41',
                    borderWidth: 1,
                    data: productthree
                }, {
                    label: '2 KG Filled',
                    backgroundColor: '#0061f2',
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
            type: 'bar',

            // The data for our dataset
            data: {
                labels: xtarline,
                datasets: [{
                    label: 'Target Tanks',
                    backgroundColor: '#81C784', 
                    borderColor: '#00ac69',
                    borderWidth: 1,
                    data: tartotal
                }, {
                    label: 'Complete Tanks',
                    backgroundColor: '#EC7063',
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

    // function vehicleavailablestock(){ //alert('IN');
    //     $.ajax({
    //         type: "POST",
    //         data: {},
    //         url: 'getprocess/getvehicleavailablestock.php',
    //         success: function(result) {//alert(result);
    //             $('#viewavailablevehicle').html(result);
    //         }
    //     });
    // }

    // function employeetarget(){ //alert('IN');
    //     $.ajax({
    //         type: "POST",
    //         data: {},
    //         url: 'getprocess/getmonthlyemployeetarget.php',
    //         success: function(result) {//alert(result);
    //             $('#viewemployeetarget').html(result);
    //         }
    //     });
    // }

    // function vehicletarget(){ //alert('IN');
    //     $.ajax({
    //         type: "POST",
    //         data: {},
    //         url: 'getprocess/getmonthlyvehicletarget.php',
    //         success: function(result) {//alert(result);
    //             $('#viewvehicletarget').html(result);
    //         }
    //     });
    // }
</script>
<?php include "include/footer.php"; ?>
