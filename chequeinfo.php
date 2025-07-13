<?php 
include "include/header.php";  

$sql="SELECT `tbl_bank_account`.`accountno`, `tbl_bank`.`bankname`, `tbl_bank_branch`.`branchname`, `tbl_cheque_info`.`startno`, `tbl_cheque_info`.`endno`, `tbl_cheque_info`.`idtbl_cheque_info`, `tbl_cheque_info`.`status` FROM `tbl_cheque_info` LEFT JOIN `tbl_bank_account` ON `tbl_bank_account`.`idtbl_bank_account`=`tbl_cheque_info`.`tbl_bank_account_idtbl_bank_account` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_cheque_info`.`tbl_bank_idtbl_bank` LEFT JOIN `tbl_bank_branch` ON `tbl_bank_branch`.`idtbl_bank_branch`=`tbl_cheque_info`.`tbl_bank_branch_idtbl_bank_branch` WHERE `tbl_cheque_info`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank=$conn->query($sqlbank);

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
                            <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                            <span>Cheque Info</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/chequeinfoprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank*</label>
                                        <select class="form-control form-control-sm" name="bank" id="bank" required>
                                            <option value="">Select</option>
                                            <?php if($resultbank->num_rows > 0) {while ($rowbank = $resultbank-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank Branch*</label>
                                        <select class="form-control form-control-sm" name="bankbranch" id="bankbranch" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account No*</label>
                                        <select class="form-control form-control-sm" name="accountno" id="accountno" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Start No*</label>
                                        <input type="text" class="form-control form-control-sm" name="startno" id="startno" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">End No*</label>
                                        <input type="text" class="form-control form-control-sm" name="endno" id="endno" required>
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
                                            <th>Start No</th>
                                            <th>End No</th>
                                            <th>Account No</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_cheque_info'] ?></td>
                                            <td><?php echo $row['startno'] ?></td>
                                            <td><?php echo $row['endno'] ?></td>
                                            <td><?php echo $row['accountno'] ?></td>
                                            <td><?php echo $row['bankname'] ?></td>
                                            <td><?php echo $row['branchname'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_cheque_info'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statuschequeinfo.php?record=<?php echo $row['idtbl_cheque_info'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statuschequeinfo.php?record=<?php echo $row['idtbl_cheque_info'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statuschequeinfo.php?record=<?php echo $row['idtbl_cheque_info'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
                    url: 'getprocess/getchequeinfo.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accountno').val(obj.accountno);                       
                        $('#bank').val(obj.bank);                       
                        $('#bankbranch').val(obj.bankbranch);                       
                        $('#startno').val(obj.startno);  
                        $('#endno').val(obj.endno);  

                        bankbranch(obj.bank, obj.bankbranch);              
                        bankaccountno(obj.bankbranch, obj.accountno);              

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $('#bank').change(function(){
            var bank = $(this).val();
            bankbranch(bank, '');
        });
        $('#bankbranch').change(function(){
            var bankbranch = $(this).val();
            bankaccountno(bankbranch, '');
        });
    });

    function bankbranch(bank, branch){
        $.ajax({
            type: "POST",
            data: {
                bank: bank
            },
            url: 'getprocess/getbankbranchaccobank.php',
            success: function(result) {
                var objfirst = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].branchid + '">';
                    html += objfirst[i].branch;
                    html += '</option>';
                });

                $('#bankbranch').empty().append(html);

                if(branch!=''){
                    $('#bankbranch').val(branch);
                }
            }
        });
    }
    function bankaccountno(bankbranch, accountno){
        $.ajax({
            type: "POST",
            data: {
                bankbranch: bankbranch
            },
            url: 'getprocess/getaccountnoaccobankbranch.php',
            success: function(result) { 
                var objfirst = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].accountid + '">';
                    html += objfirst[i].account;
                    html += '</option>';
                });

                $('#accountno').empty().append(html);

                if(accountno!=''){
                    $('#accountno').val(accountno);
                }
            }
        });
    }

</script>
<?php include "include/footer.php"; ?>
