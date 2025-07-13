<?php 
include "include/header.php";  

$sql="SELECT `tbl_master`.*, `tbl_company`.`name`, `tbl_company_branch`.`branch`, `tbl_finacial_year`.`year` FROM `tbl_master` LEFT JOIN `tbl_company_branch` ON `tbl_company_branch`.`idtbl_company_branch`=`tbl_master`.`tbl_company_branch_idtbl_company_branch` LEFT JOIN `tbl_finacial_year` ON `tbl_finacial_year`.`idtbl_finacial_year`=`tbl_master`.`tbl_finacial_year_idtbl_finacial_year` LEFT JOIN `tbl_company` ON `tbl_company`.`idtbl_company`=`tbl_company_branch`.`tbl_company_idtbl_company` WHERE `tbl_master`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

$sqlfinancial="SELECT `idtbl_finacial_year`, `year` FROM `tbl_finacial_year` WHERE `status`=1";
$resultfinancial=$conn->query($sqlfinancial);

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
                            <div class="page-header-icon"><i data-feather="server"></i></div>
                            <span>Financial Year & Branch</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/financialyearbranchprocess.php" method="post" autocomplete="off">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Company*</label>
                                            <select class="form-control form-control-sm" name="company" id="company" required>
                                                <option value="">select</option>
                                                <?php if($resultcompany->num_rows > 0) {while ($rowcompany = $resultcompany-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowcompany['idtbl_company'] ?>"><?php echo $rowcompany['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Company Branch</label>
                                            <select class="form-control form-control-sm" name="companybranch" id="companybranch" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Financial Year*</label>
                                            <select class="form-control form-control-sm" name="fianncialyear" id="fianncialyear" required>
                                                <option value="">Select</option>
                                                <?php if($resultfinancial->num_rows > 0) {while ($rowfinancial = $resultfinancial-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowfinancial['idtbl_finacial_year'] ?>"><?php echo $rowfinancial['year'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Company</th>
                                            <th>Company Branch</th>
                                            <th>Financial Year</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_master'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['branch'] ?></td>
                                            <td><?php echo $row['year'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_master'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusfinancialyearbranch.php?record=<?php echo $row['idtbl_master'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusfinancialyearbranch.php?record=<?php echo $row['idtbl_master'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusfinancialyearbranch.php?record=<?php echo $row['idtbl_master'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('#company').change(function(){
            var company = $(this).val();

            branch(company, '');
        });

        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getfinancialyearbranch.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#company').val(obj.company);   
                        $('#fianncialyear').val(obj.year);   

                        branch(obj.company, obj.branch);       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

    function branch(company, value){
        $.ajax({
            type: "POST",
            data: {
                company: company
            },
            url: 'getprocess/getcompanybranchaccocompany.php',
            success: function(result) { //alert(result);
                var objfirst = JSON.parse(result);

                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].id + '">';
                    html += objfirst[i].branch;
                    html += '</option>';
                });

                $('#companybranch').empty().append(html);

                if(value!=''){
                    $('#companybranch').val(value);
                }
            }
        });
    }

</script>
<?php include "include/footer.php"; ?>
