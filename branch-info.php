<?php
include "include/header.php";

$sql="SELECT `tbl_bank_branch`.*, `tbl_bank`.`bankname` FROM `tbl_bank_branch` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_bank_branch`.`tbl_bank_idtbl_bank` WHERE `tbl_bank_branch`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
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
                    <div class="page-header-content d-flex align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                                <span>Branch Information</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <form class="" action="process/bankbranchinfoprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank</label>
                                        <select class="form-control form-control-sm" id="bank" name="bank" >
                                            <option value="">Select</option>
                                            <?php while($rowbank = $resultbank->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Code</label>
                                        <input class="form-control form-control-sm" type="text" name="code" id="code" minlength="2" maxlength="2">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Branch Name</label>
                                        <input class="form-control form-control-sm" type="text" id="branch" name="branch" placeholder="Your Bank Name">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Telephone</label>
                                        <input class="form-control form-control-sm" type="text" id="telephone" name="telephone" placeholder="Your Contact Number">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Fax</label>
                                        <input class="form-control form-control-sm" type="text" id="fax" name="fax" placeholder="Your Fax Number">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address</label>
                                        <textarea class="form-control form-control-sm" type="text" id="address" name="address" placeholder="Your Address"></textarea>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="table-responsive-sm ">
                                    <table  id="dataTable" class="table table-sm w-100 table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Bank</th>
                                                <th>Code</th>
                                                <th>Branch Name</th>
                                                <th>Phone</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($result->num_rows > 0){while($row = $result->fetch_assoc()){?>
                                            <tr>
                                                <td><?php echo $row['idtbl_bank_branch']; ?></td>
                                                <td><?php echo $row['bankname']; ?></td>
                                                <td><?php echo $row['code']; ?></td>
                                                <td><?php echo $row['branchname']; ?></td>
                                                <td><?php echo$row['phone']; ?></td>
                                                <td class="text-right">
                                                    <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_bank_branch'] ?>"><i data-feather="edit-2"></i></button>
                                                    <?php if($row['status']==1){ ?>
                                                    <a href="process/statusbankbranchinfo.php?record=<?php echo $row['idtbl_bank_branch'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                    <?php }else{ ?>
                                                    <a href="process/statusbankbranchinfo.php?record=<?php echo $row['idtbl_bank_branch'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                    <?php } ?>
                                                    <a href="process/statusbankbranchinfo.php?record=<?php echo $row['idtbl_bank_branch'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
                                                </td>
                                            </tr>
                                            <?php }}?>
                                        </tbody>
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
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
                    url: 'getprocess/getbranchinfo.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#bank').val(obj.bank);
                        $('#code').val(obj.code);
                        $('#branch').val(obj.branchname);
                        $('#telephone').val(obj.phone);
                        $('#fax').val(obj.fax);
                        $('#address').val(obj.address);
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');

                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php";?>
