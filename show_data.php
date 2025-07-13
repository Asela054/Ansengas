<?php 
include "include/header.php";  


$statementRefno = isset($_GET['refno'])?$_GET['refno']:'44';
$pre_char = isset($_GET['refch'])?$_GET['refch']:'D';

$sqlsub="SELECT trano, tratype, refid, refno, seqno, crdr, acccode, accamount, narration, totamount, tradate, paytype, companycode FROM `tbl_account_transaction` WHERE trano=CONCAT('".$pre_char."', SUBSTRING(CONCAT('000000000', '".$statementRefno."'), -9, 9)) ORDER BY crdr DESC, seqno DESC";
$resultsub =$conn-> query($sqlsub); 

?>
<html>
    <body>
    	<table width="100%">
        	<tr>
                <td>trano</td>
                <td>tratype</td>
                <td>refid</td>
                <td>refno</td>
                <td>seqno</td>
                <td>crdr</td>
                <td>acccode</td>
                <td>accamount</td>
                <td>narration</td>
                <td>totamount</td>
                <td>tradate</td>
                <td>paytype</td>
                <td>companycode</td>
            </tr>
            
            <?php if($resultsub->num_rows > 0) {while ($rowsub=$resultsub->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $rowsub['trano']; ?></td>
                <td><?php echo $rowsub['tratype']; ?></td>
                <td><?php echo $rowsub['refid']; ?></td>
                <td><?php echo $rowsub['refno']; ?></td>
                <td><?php echo $rowsub['seqno']; ?></td>
                <td><?php echo $rowsub['crdr']; ?></td>
                <td><?php echo $rowsub['acccode']; ?></td>
                <td><?php echo $rowsub['accamount']; ?></td>
                <td><?php echo $rowsub['narration']; ?></td>
                <td><?php echo $rowsub['totamount']; ?></td>
                <td><?php echo $rowsub['tradate']; ?></td>
                <td><?php echo $rowsub['paytype']; ?></td>
                <td><?php echo $rowsub['companycode']; ?></td>
            </tr>
            <?php }} ?>
        </table>
    </body>
</html>