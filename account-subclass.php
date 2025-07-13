<?php
include "include/header.php";

$sql="SELECT `tbl_subclass`.*, `tbl_mainclass`.`class` FROM `tbl_subclass` LEFT JOIN `tbl_mainclass` ON `tbl_mainclass`.`code`=`tbl_subclass`.`mainclasscode` WHERE `tbl_subclass`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlmainclass="SELECT `code`, `class` FROM `tbl_mainclass` WHERE `status` IN (1,2)";
$resultmainclass =$conn-> query($sqlmainclass); 

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
                    <div class="page-header-content text-center d-md-flex align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="server"></i></div>
                                <span>Sub Category</span>
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
                                <form class=" " action="process/accountsubclassprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account Category</label>
                                        <select class="form-control form-control-sm" name="mainclass" id="mainclass" required>
                                            <option value="">Select</option>
                                            <?php while($rowmainclass = $resultmainclass->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowmainclass['code'] ?>"><?php echo $rowmainclass['class'].' - '.$rowmainclass['code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Code</label>
                                        <input class="form-control form-control-sm" type="text" minlength="2" maxlength="2" id="code" name="code">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Sub Category</label>
                                        <input class="form-control form-control-sm" type="text" id="subclass" name="subclass">
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
                                    <table id="dataTable" class="table dataTable table-sm w-100 table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Account Category</th>
                                                <th>Sub Category</th>
                                                <th>Code</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($result->num_rows > 0){ while($row = $result->fetch_assoc()){ ?>
                                                <tr>
                                                    <td><?php echo $row['idtbl_subclass']; ?></td>
                                                    <td><?php echo $row['class'].' - '.$row['mainclasscode']; ?></td>
                                                    <td><?php echo $row['code']; ?></td>
                                                    <td><?php echo $row['subclass']; ?></td>
                                                    <td class="text-right">
                                                        <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_subclass'] ?>"><i data-feather="edit-2"></i></button>
                                                        <?php if($row['status']==1){ ?>
                                                        <a href="process/statusaccountsubclass.php?record=<?php echo $row['idtbl_subclass'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                        <?php }else{ ?>
                                                        <a href="process/statusaccountsubclass.php?record=<?php echo $row['idtbl_subclass'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                        <?php } ?>
                                                        <a href="process/statusaccountsubclass.php?record=<?php echo $row['idtbl_subclass'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    window.mainclassesArray = new Array();
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
                    url: 'getprocess/getsubclass.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#mainclass').val(obj.mainclasscode);
                        $('#subclass').val(obj.subclass);
                        $('#code').val(obj.code);
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');

                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>
