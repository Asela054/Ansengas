<?php
include "include/header.php";
include "include/topnavbar.php";

$banksql = "SELECT * FROM tbl_bank WHERE status IN (1,2)";
$resultbanks = $conn->query($banksql);

?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content d-md-flex text-right align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                                <span>Bank</span>
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
                                <form class="m-2" action="process/bankinfoprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Code</label>
                                        <input class="form-control form-control-sm" type="text" id="code" maxlength="2" minlength="2" name="code" value="">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank Name</label>
                                        <input class="form-control form-control-sm" type="text" id="name" name="name" value="">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-md-9">

                                <div class="table-responsive-sm w-100">
                                    <table id="dataTable" class="table table-sm w-100 table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Bank Name</th>
                                                <th>Code</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultbanks->num_rows > 0){ while($row = $resultbanks->fetch_assoc()){ ?>
                                            <tr>
                                                <td><?php echo $row['idtbl_bank']; ?></td>
                                                <td><?php echo $row['bankname']; ?></td>
                                                <td><?php echo $row['code']; ?></td>
                                                <td class="text-right">
                                                    <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_bank'] ?>"><i data-feather="edit-2"></i></button>
                                                    <?php if($row['status']==1){ ?>
                                                    <a href="process/statusbankinfo.php?record=<?php echo $row['idtbl_bank'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                    <?php }else{ ?>
                                                    <a href="process/statusbankinfo.php?record=<?php echo $row['idtbl_bank'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                    <?php } ?>
                                                    <a href="process/statusbankinfo.php?record=<?php echo $row['idtbl_bank'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
                    url: 'getprocess/getbankinfo.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#code').val(obj.code);
                        $('#name').val(obj.name);
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
