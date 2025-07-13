<?php 
include "include/header.php";  

$sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`name`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1";
$result =$conn-> query($sql); 

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
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
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Invoice View</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Executive Name</th>
                                            <th>Area</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-right">Balance</th>
                                            <!-- <th class="text-right">Excess Amount</th> -->
                                            <th>Payment</th>
                                            <th>Cancel Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
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
<!-- Modal Warning -->
<div class="modal fade" style="z-index: 2000; " id="warningModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                Can't cancel this invoice, because firstly cancel payment receipt. Thank you.
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
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

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/invoiceviewlist.php",
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
                // {
                //     "targets": -1,
                //     "className": 'text-right',
                //     "data": null,
                //     "render": function(data, type, full) {
                //         var excessPayment = parseFloat(full['excess_payment']).toFixed(2);
                //         if (excessPayment < 0) {
                //             return '0.00';
                //         } else {
                //             return addCommas(excessPayment);
                //         }
                //     }
                // },
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
                    "className": '',
                    "data": null,
                    "render": function(data, type, full, meta) {
                        if (full['status'] == 3) {
                            $(meta.row).addClass('bg-danger text-white');
                            return '<span class="font-weight-bold">Cancelled</span>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-outline-dark btn-sm btnView mr-1 ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button += '" id="' + full['idtbl_invoice'] + '"><i class="fas fa-eye"></i></button>';
                        if (full['paymentcomplete'] == 0) {
                            button += '<a href="process/statusinvoice.php?record=' + full['idtbl_invoice'] + '&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';
                            if (deletecheck == 0) {
                                button += 'd-none';
                            }
                            button += '"><i class="far fa-trash-alt"></i></a>';
                        } else {
                            button += '<button class="btn btn-outline-danger btn-sm ';
                            if (deletecheck == 0) {
                                button += 'd-none';
                            }
                            button += '" data-toggle="modal" data-target="#warningModal"><i class="far fa-trash-alt"></i></button>';
                        }
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
        document.getElementById('btnreceiptprint').addEventListener ("click", print);
    });

    function print() {
        printJS({
            printable: 'viewreceiptprint',
            type: 'html',
            style: '@page { size: A4 portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
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
