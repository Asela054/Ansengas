<?php

// specify optional header includes in this array.
$optional_header_includes = ['magnific-popup'];

include "include/header.php";  

include "include/topnavbar.php";

$sqlfinancialyear = "SELECT * FROM `tbl_finacial_year`where status IN (1,2)";
$resultfinancialyear = $conn->query($sqlfinancialyear);

?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content d-md-flex text-sm-right align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="server"></i></div>
                                <span>Financial Years</span>
                            </h1>
                        </div>
                        <div class="col-md-5 text-right">
                            <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="modal" data-target="#setActiveFinancialYearModal"><i data-feather="check-circle"></i>  Set Active Financial Year</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <form class="p-1 m-2" action="process/financialyearprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Year</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd2a rounded-0" id="yearinput" name="year" value="" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text rounded-0" id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Start Date </label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd1a rounded-0" id="startdate" name="startdate" value="" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text rounded-0" id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">End Date </label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd1a rounded-0" id="enddate" name="enddate" value="" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text rounded-0" id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Description </label>
                                        <textarea class="form-control form-control-sm" required type="date" id="description" name="description" value="" placeholder="Descrption"></textarea>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="table-responsive-sm">
                                    <table id="dataTable" class="table table-sm w-100 table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Year</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $financialsyearsArray = array();
                                            if($resultfinancialyear->num_rows > 0){
                                                while($row=$resultfinancialyear->fetch_assoc()){
                                                    $tempArray = array();
                                                    $tempArray['id'] =  $row['idtbl_finacial_year'];
                                                    $tempArray['year'] =  $row['year'];
                                                    $financialsyearsArray[] = $tempArray;
                                            ?>
                                            <tr class="<?php if($row['actstatus'] == 1){echo 'table-success'; } ?>">
                                                <td><?php echo $row['idtbl_finacial_year']?></td>
                                                <td><?php echo $row['year']; ?></td>
                                                <td><?php echo $row['startdate']; ?></td>
                                                <td><?php echo $row['enddate']; ?></td>
                                                <td><?php echo $row['desc']; ?></td>
                                                <td class="text-right">
                                                    <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_finacial_year'] ?>"><i data-feather="edit-2"></i></button>
                                                    <?php if($row['status']==1){ ?>
                                                    <a href="process/statusfinancialyear.php?record=<?php echo $row['idtbl_finacial_year'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                    <?php }else{ ?>
                                                    <a href="process/statusfinancialyear.php?record=<?php echo $row['idtbl_finacial_year'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                    <?php } ?>
                                                    <a href="process/statusfinancialyear.php?record=<?php echo $row['idtbl_finacial_year'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div class="modal fade" id="addFinancialYearModal" tabindex="-1" role="dialog" aria-labelledby="addFinancialYearModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFinancialYearModalLabel">Add Financial Year</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button class="btn btn-sm form-control-sm btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-sm form-control-sm btn-primary"  data-dismiss="modal" onclick="alert('Data added')" type="button">Add New</button></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="setActiveFinancialYearModal" tabindex="-1" role="dialog"
            aria-labelledby="setActiveFinancialYearModal" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="process/setactivefinancialyear.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="setActiveFinancialYearModalLabel">Set Active Financial Year</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-1">
                                <label for="financialyearselect">Select Year</label>
                                <select class="form-control form-control-sm" required id="financialyearselect"
                                    name="financialyearselect">
                                    <option value="">Select</option>
                                    <?php if(count($financialsyearsArray) > 0){foreach($financialsyearsArray as $key=>$value){ ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['year']; ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <div class="text-center">
                                    <span class="text-danger small">This cannot be undone.</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm form-control-sm btn-secondary" type="button" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm form-control-sm btn-primary">Ok</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            orientation: "bottom",
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
        $('.dpd2a').datepicker('remove');
        $('.dpd2a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy',
            viewMode: "years", 
            minViewMode: "years"
        });

        $('#dataTable').DataTable();
        $('.image-link').magnificPopup({type:'image'});
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getfinancialyear.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#yearinput').val(obj.year);
                        $('#startdate').val(obj.startdate);
                        $('#enddate').val(obj.enddate);
                        $('#description').html(obj.description);
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');

                    }
                });
            }
        });
    });
</script>
<?php

// specify optional header includes in this array.
$optional_footer_includes = ['magnific-popup'];

include "include/footer.php";
?>
