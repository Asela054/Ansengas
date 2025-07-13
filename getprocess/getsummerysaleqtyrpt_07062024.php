<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$arrayproduct=array();
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1";
$resultproduct=$conn->query($sqlproduct);
while($rowproduct=$resultproduct->fetch_assoc()){
    $obj=new stdClass();
    $obj->idtbl_product=$rowproduct['idtbl_product'];
    $obj->product_name=$rowproduct['product_name'];

    array_push($arrayproduct, $obj);
}

$array2=array();
$array5=array();
$array125=array();
$array375=array();

$arrayP2_2=array();
$arrayP2_5=array();
$arrayP2_125=array();
$arrayP2_375=array();

$sql="SELECT SUM(`tbl_invoice_detail`.`refillqty`) AS `saleqty`, `tbl_invoice_detail`.`refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '1' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`refillqty`)) AS `totalamount`, (`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`refillprice` UNION ALL SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `saleqty`, `tbl_invoice_detail`.`newprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '2' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`newprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`newqty`)) AS `totalamount`, (`tbl_invoice_detail`.`newprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`newprice` UNION ALL SELECT SUM(`tbl_invoice_detail`.`refillqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '1' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`refillqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_refillprice` UNION ALL SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_newprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '2' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_newprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`newqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_newprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_newprice`";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()){
    if($row['producttype']==1){
        if($row['tbl_product_idtbl_product']==1){
            $obj125=new stdClass();
            $obj125->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $obj125->withvatprice=$row['withvatprice'];
            $obj125->saleqty=$row['saleqty'];
            $obj125->totalamount=$row['totalamount'];

            array_push($array125, $obj125);
        }
        else if($row['tbl_product_idtbl_product']==2){
            $obj375=new stdClass();
            $obj375->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $obj375->withvatprice=$row['withvatprice'];
            $obj375->saleqty=$row['saleqty'];
            $obj375->totalamount=$row['totalamount'];

            array_push($array375, $obj375);
        }
        else if($row['tbl_product_idtbl_product']==4){
            $obj5=new stdClass();
            $obj5->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $obj5->withvatprice=$row['withvatprice'];
            $obj5->saleqty=$row['saleqty'];
            $obj5->totalamount=$row['totalamount'];

            array_push($array5, $obj5);
        }
        else if($row['tbl_product_idtbl_product']==6){
            $obj2=new stdClass();
            $obj2->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $obj2->withvatprice=$row['withvatprice'];
            $obj2->saleqty=$row['saleqty'];
            $obj2->totalamount=$row['totalamount'];

            array_push($array2, $obj2);
        }
    }else if($row['producttype']==2){
        if($row['tbl_product_idtbl_product']==1){
            $objP2_125=new stdClass();
            $objP2_125->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP2_125->withvatprice=$row['withvatprice'];
            $objP2_125->saleqty=$row['saleqty'];
            $objP2_125->totalamount=$row['totalamount'];

            array_push($arrayP2_125, $objP2_125);
        }
        else if($row['tbl_product_idtbl_product']==2){
            $obj375=new stdClass();
            $obj375->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $obj375->withvatprice=$row['withvatprice'];
            $obj375->saleqty=$row['saleqty'];
            $obj375->totalamount=$row['totalamount'];

            array_push($arrayP2_375, $objP2_375);
        }
        else if($row['tbl_product_idtbl_product']==4){
            $objP2_5=new stdClass();
            $objP2_5->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP2_5->withvatprice=$row['withvatprice'];
            $objP2_5->saleqty=$row['saleqty'];
            $objP2_5->totalamount=$row['totalamount'];

            array_push($arrayP2_5, $objP2_5);
        }
        else if($row['tbl_product_idtbl_product']==6){
            $objP2_2=new stdClass();
            $objP2_2->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP2_2->withvatprice=$row['withvatprice'];
            $objP2_2->saleqty=$row['saleqty'];
            $objP2_2->totalamount=$row['totalamount'];

            array_push($arrayP2_2, $objP2_2);
        }
    }
}

$countarray=[count($array125),count($array375),count($array5),count($array2)];
$maxcount=max($countarray);

$countarrayP2=[count($arrayP2_125),count($arrayP2_375),count($arrayP2_5),count($arrayP2_2)];
$maxcountP2=max($countarrayP2);

$mainarray=array();
for($i=0; $maxcount>$i; $i++){
    $obj=new stdClass();
    if(count($array125)>$i){$obj->text125=number_format($array125[$i]->withvatprice, 2).' X '.$array125[$i]->saleqty.' = '.number_format($array125[$i]->totalamount, 2);}
    else{$obj->text125='';}
    if(count($array375)>$i){$obj->text375=number_format($array375[$i]->withvatprice).' X '.$array375[$i]->saleqty.' = '.number_format($array375[$i]->totalamount, 2);}
    else{$obj->text375='';}
    if(count($array5)>$i){$obj->text5=number_format($array5[$i]->withvatprice).' X '.$array5[$i]->saleqty.' = '.number_format($array5[$i]->totalamount, 2);}
    else{$obj->text5='';}
    if(count($array2)>$i){$obj->text2=number_format($array2[$i]->withvatprice).' X '.$array2[$i]->saleqty.' = '.number_format($array2[$i]->totalamount, 2);}
    else{$obj->text2='';}

    array_push($mainarray, $obj);
}

$mainarray2=array();
for($i=0; $maxcountP2>$i; $i++){
    $obj_2=new stdClass();
    if(count($arrayP2_125)>$i){$obj_2->textP2_125=number_format($arrayP2_125[$i]->withvatprice, 2).' X '.$arrayP2_125[$i]->saleqty.' = '.number_format($arrayP2_125[$i]->totalamount, 2);}
    else{$obj_2->textP2_125='';}
    if(count($arrayP2_375)>$i){$obj_2->textP2_375=number_format($arrayP2_375[$i]->withvatprice).' X '.$arrayP2_375[$i]->saleqty.' = '.number_format($arrayP2_375[$i]->totalamount, 2);}
    else{$obj_2->textP2_375='';}
    if(count($arrayP2_5)>$i){$obj_2->textP2_5=number_format($arrayP2_5[$i]->withvatprice).' X '.$arrayP2_5[$i]->saleqty.' = '.number_format($arrayP2_5[$i]->totalamount, 2);}
    else{$obj_2->textP2_5='';}
    if(count($arrayP2_2)>$i){$obj_2->textP2_2=number_format($arrayP2_2[$i]->withvatprice).' X '.$arrayP2_2[$i]->saleqty.' = '.number_format($arrayP2_2[$i]->totalamount, 2);}
    else{$obj_2->textP2_2='';}

    array_push($mainarray2, $obj_2);
}
?>
<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <?php foreach($arrayproduct as $rowproductlist){ ?>
            <th><?php echo $rowproductlist->product_name ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-dark font-weight-bold" colspan="4">Refill Cylinders</td> <!-- Set colspan for the first set -->
        </tr>
        <?php foreach($mainarray as $rowmaindata){ ?>
        <tr>
            <td><?php echo $rowmaindata->text125 ?></td>
            <td><?php echo $rowmaindata->text375 ?></td>
            <td><?php echo $rowmaindata->text5 ?></td>
            <td><?php echo $rowmaindata->text2 ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="text-dark font-weight-bold" colspan="4">New Cylinders</td> <!-- Set colspan for the second set -->
        </tr>
        <?php foreach($mainarray2 as $rowmaindata2){ ?>
        <tr>
            <td><?php echo $rowmaindata2->textP2_125 ?></td>
            <td><?php echo $rowmaindata2->textP2_375 ?></td>
            <td><?php echo $rowmaindata2->textP2_5 ?></td>
            <td><?php echo $rowmaindata2->textP2_2 ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>