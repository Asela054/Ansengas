<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$arrayproduct=array();
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1 ORDER BY orderlevel ASC";
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

$arrayP3_2=array();
$arrayP3_5=array();
$arrayP3_125=array();
$arrayP3_375=array();

$arrayP4_2=array();
$arrayP4_5=array();
$arrayP4_125=array();
$arrayP4_375=array();

$nettotal=0;

$sql="SELECT SUM(`tbl_invoice_detail`.`refillqty`) AS `saleqty`, `tbl_invoice_detail`.`refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '1' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`refillqty`)) AS `totalamount`, (`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`refillprice` 
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`refillqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '1' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`refillqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_refillprice` 
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `saleqty`, `tbl_invoice_detail`.`newprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '2' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`newprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`newqty`)) AS `totalamount`, (`tbl_invoice_detail`.`newprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`newprice` 
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_newprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '2' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_newprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`newqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_newprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_newprice`
UNION ALL
SELECT SUM(`tbl_invoice_detail`.`trustqty`) AS `saleqty`, `tbl_invoice_detail`.`refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '3' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`trustqty`)) AS `totalamount`, (`tbl_invoice_detail`.`refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`refillprice`
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`trustqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_refillprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '3' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`trustqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_refillprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_refillprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_refillprice`
UNION ALL
SELECT SUM(`tbl_invoice_detail`.`emptyqty`) AS `saleqty`, `tbl_invoice_detail`.`emptyprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '4' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`emptyprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`emptyqty`)) AS `totalamount`, (`tbl_invoice_detail`.`emptyprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`emptyprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`emptyprice`
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`emptyqty`) AS `saleqty`, `tbl_invoice_detail`.`encustomer_emptyprice` AS `productprice`, `tbl_product`.`product_name`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, '4' AS `producttype`, `tbl_invoice`.`vat`, ((`tbl_invoice_detail`.`encustomer_emptyprice`*(100+`tbl_invoice`.`vat`)/100)*SUM(`tbl_invoice_detail`.`emptyqty`)) AS `totalamount`, (`tbl_invoice_detail`.`encustomer_emptyprice`*(100+`tbl_invoice`.`vat`)/100) AS `withvatprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_emptyprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_emptyprice`";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()){
    $nettotal+=$row['totalamount'];
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
    }
    else if($row['producttype']==2){
        if($row['tbl_product_idtbl_product']==1){
            $objP2_125=new stdClass();
            $objP2_125->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP2_125->withvatprice=$row['withvatprice'];
            $objP2_125->saleqty=$row['saleqty'];
            $objP2_125->totalamount=$row['totalamount'];

            array_push($arrayP2_125, $objP2_125);
        }
        else if($row['tbl_product_idtbl_product']==2){
            $objP2_375=new stdClass();
            $objP2_375->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP2_375->withvatprice=$row['withvatprice'];
            $objP2_375->saleqty=$row['saleqty'];
            $objP2_375->totalamount=$row['totalamount'];

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
    else if($row['producttype']==3){
        if($row['tbl_product_idtbl_product']==1){
            $objP3_125=new stdClass();
            $objP3_125->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP3_125->withvatprice=$row['withvatprice'];
            $objP3_125->saleqty=$row['saleqty'];
            $objP3_125->totalamount=$row['totalamount'];

            array_push($arrayP3_125, $objP3_125);
        }
        else if($row['tbl_product_idtbl_product']==2){
            $objP3_375=new stdClass();
            $objP3_375->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP3_375->withvatprice=$row['withvatprice'];
            $objP3_375->saleqty=$row['saleqty'];
            $objP3_375->totalamount=$row['totalamount'];

            array_push($arrayP3_375, $objP3_375);
        }
        else if($row['tbl_product_idtbl_product']==4){
            $objP3_5=new stdClass();
            $objP3_5->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP3_5->withvatprice=$row['withvatprice'];
            $objP3_5->saleqty=$row['saleqty'];
            $objP3_5->totalamount=$row['totalamount'];

            array_push($arrayP3_5, $objP3_5);
        }
        else if($row['tbl_product_idtbl_product']==6){
            $objP3_2=new stdClass();
            $objP3_2->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP3_2->withvatprice=$row['withvatprice'];
            $objP3_2->saleqty=$row['saleqty'];
            $objP3_2->totalamount=$row['totalamount'];

            array_push($arrayP3_2, $objP3_2);
        }
    }
    else if($row['producttype']==4){
        if($row['tbl_product_idtbl_product']==1){
            $objP4_125=new stdClass();
            $objP4_125->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP4_125->withvatprice=$row['withvatprice'];
            $objP4_125->saleqty=$row['saleqty'];
            $objP4_125->totalamount=$row['totalamount'];

            array_push($arrayP4_125, $objP4_125);
        }
        else if($row['tbl_product_idtbl_product']==2){
            $objP4_375=new stdClass();
            $objP4_375->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP4_375->withvatprice=$row['withvatprice'];
            $objP4_375->saleqty=$row['saleqty'];
            $objP4_375->totalamount=$row['totalamount'];

            array_push($arrayP4_375, $objP4_375);
        }
        else if($row['tbl_product_idtbl_product']==4){
            $objP4_5=new stdClass();
            $objP4_5->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP4_5->withvatprice=$row['withvatprice'];
            $objP4_5->saleqty=$row['saleqty'];
            $objP4_5->totalamount=$row['totalamount'];

            array_push($arrayP4_5, $objP4_5);
        }
        else if($row['tbl_product_idtbl_product']==6){
            $objP4_2=new stdClass();
            $objP4_2->tbl_product_idtbl_product=$row['tbl_product_idtbl_product'];
            $objP4_2->withvatprice=$row['withvatprice'];
            $objP4_2->saleqty=$row['saleqty'];
            $objP4_2->totalamount=$row['totalamount'];

            array_push($arrayP4_2, $objP4_2);
        }
    }
}

$countarray=[count($array125),count($array375),count($array5),count($array2)];
$maxcount=max($countarray);

$countarrayP2=[count($arrayP2_125),count($arrayP2_375),count($arrayP2_5),count($arrayP2_2)];
$maxcountP2=max($countarrayP2);

$countarrayP3=[count($arrayP3_125),count($arrayP3_375),count($arrayP3_5),count($arrayP3_2)];
$maxcountP3=max($countarrayP3);

$countarrayP4=[count($arrayP4_125),count($arrayP4_375),count($arrayP4_5),count($arrayP4_2)];
$maxcountP4=max($countarrayP4);

$numberarray=array($maxcount, $maxcountP2, $maxcountP3, $maxcountP4);
$max_value = max($numberarray);

//Refill Qty Information
$mainarray=array();
for($i=0; $maxcount>$i; $i++){
    $obj=new stdClass();
    if(count($array125)>$i && $array125[$i]->saleqty>0){
        $obj->text125=number_format($array125[$i]->withvatprice, 2).' X '.$array125[$i]->saleqty.' = '.number_format($array125[$i]->totalamount, 2);
        $obj->totaltext125=$array125[$i]->totalamount;
        $obj->totalqty125=$array125[$i]->saleqty;
    }
    else{
        $obj->text125='';
        $obj->totaltext125=0;
        $obj->totalqty125=0;
    }
    if(count($array375)>$i && $array375[$i]->saleqty>0){
        $obj->text375=number_format($array375[$i]->withvatprice, 2).' X '.$array375[$i]->saleqty.' = '.number_format($array375[$i]->totalamount, 2);
        $obj->totaltext375=$array375[$i]->totalamount;
        $obj->totalqty375=$array375[$i]->saleqty;
    }
    else{
        $obj->text375='';
        $obj->totaltext375=0;
        $obj->totalqty375=0;
    }
    if(count($array5)>$i && $array5[$i]->saleqty>0){
        $obj->text5=number_format($array5[$i]->withvatprice, 2).' X '.$array5[$i]->saleqty.' = '.number_format($array5[$i]->totalamount, 2);
        $obj->totaltext5=$array5[$i]->totalamount;
        $obj->totalqty5=$array5[$i]->saleqty;
    }
    else{
        $obj->text5='';
        $obj->totaltext5=0;
        $obj->totalqty5=0;
    }
    if(count($array2)>$i && $array2[$i]->saleqty>0){
        $obj->text2=number_format($array2[$i]->withvatprice, 2).' X '.$array2[$i]->saleqty.' = '.number_format($array2[$i]->totalamount, 2);
        $obj->totaltext2=$array2[$i]->totalamount;
        $obj->totalqty2=$array2[$i]->saleqty;
    }
    else{
        $obj->text2='';
        $obj->totaltext2=0;
        $obj->totalqty2=0;
    }

    array_push($mainarray, $obj);
}

//New Qty Information
$mainarray2=array();
for($i=0; $maxcountP2>$i; $i++){
    $obj_2=new stdClass();
    if(count($arrayP2_125)>$i && $arrayP2_125[$i]->saleqty>0){
        $obj_2->textP2_125=number_format($arrayP2_125[$i]->withvatprice, 2).' X '.$arrayP2_125[$i]->saleqty.' = '.number_format($arrayP2_125[$i]->totalamount, 2);
        $obj_2->totaltextP2_125=$arrayP2_125[$i]->totalamount;
        $obj_2->totalqtyP2_125=$arrayP2_125[$i]->saleqty;
    }
    else{
        $obj_2->textP2_125='';
        $obj_2->totaltextP2_125=0;
        $obj_2->totalqtyP2_125=0;
    }
    if(count($arrayP2_375)>$i && $arrayP2_375[$i]->saleqty>0){
        $obj_2->textP2_375=number_format($arrayP2_375[$i]->withvatprice, 2).' X '.$arrayP2_375[$i]->saleqty.' = '.number_format($arrayP2_375[$i]->totalamount, 2);
        $obj_2->totaltextP2_375=$arrayP2_375[$i]->totalamount;
        $obj_2->totalqtyP2_375=$arrayP2_375[$i]->saleqty;
    }
    else{
        $obj_2->textP2_375='';
        $obj_2->totaltextP2_375=0;
        $obj_2->totalqtyP2_375=0;
    }
    if(count($arrayP2_5)>$i && $arrayP2_5[$i]->saleqty>0){
        $obj_2->textP2_5=number_format($arrayP2_5[$i]->withvatprice, 2).' X '.$arrayP2_5[$i]->saleqty.' = '.number_format($arrayP2_5[$i]->totalamount, 2);
        $obj_2->totaltextP2_5=$arrayP2_5[$i]->totalamount;
        $obj_2->totalqtyP2_5=$arrayP2_5[$i]->saleqty;
    }
    else{
        $obj_2->textP2_5='';
        $obj_2->totaltextP2_5=0;
        $obj_2->totalqtyP2_5=0;
    }
    if(count($arrayP2_2)>$i && $arrayP2_2[$i]->saleqty>0){
        $obj_2->textP2_2=number_format($arrayP2_2[$i]->withvatprice, 2).' X '.$arrayP2_2[$i]->saleqty.' = '.number_format($arrayP2_2[$i]->totalamount, 2);
        $obj_2->totaltextP2_2=$arrayP2_2[$i]->totalamount;
        $obj_2->totalqtyP2_2=$arrayP2_2[$i]->saleqty;
    }
    else{
        $obj_2->textP2_2='';
        $obj_2->totaltextP2_2=0;
        $obj_2->totalqtyP2_2=0;
    }

    array_push($mainarray2, $obj_2);
}

//Trust Qty Information
$mainarray3=array();
for($i=0; $maxcountP3>$i; $i++){
    $obj_3=new stdClass();
    if(count($arrayP3_125)>$i && $arrayP3_125[$i]->saleqty>0){
        $obj_3->textP3_125=number_format($arrayP3_125[$i]->withvatprice, 2).' X '.$arrayP3_125[$i]->saleqty.' = '.number_format($arrayP3_125[$i]->totalamount, 2);
        $obj_3->totaltextP3_125=$arrayP3_125[$i]->totalamount;
        $obj_3->totalqtyP3_125=$arrayP3_125[$i]->saleqty;
    }
    else{
        $obj_3->textP3_125='';
        $obj_3->totaltextP3_125=0;
        $obj_3->totalqtyP3_125=0;
    }
    if(count($arrayP3_375)>$i && $arrayP3_375[$i]->saleqty>0){
        $obj_3->textP3_375=number_format($arrayP3_375[$i]->withvatprice, 2).' X '.$arrayP3_375[$i]->saleqty.' = '.number_format($arrayP3_375[$i]->totalamount, 2);
        $obj_3->totaltextP3_375=$arrayP3_375[$i]->totalamount;
        $obj_3->totalqtyP3_375=$arrayP3_375[$i]->saleqty;
    }
    else{
        $obj_3->textP3_375='';
        $obj_3->totaltextP3_375=0;
        $obj_3->totalqtyP3_375=0;
    }
    if(count($arrayP3_5)>$i && $arrayP3_5[$i]->saleqty>0){
        $obj_3->textP3_5=number_format($arrayP3_5[$i]->withvatprice, 2).' X '.$arrayP3_5[$i]->saleqty.' = '.number_format($arrayP3_5[$i]->totalamount, 2);
        $obj_3->totaltextP3_5=$arrayP3_5[$i]->totalamount;
        $obj_3->totalqtyP3_5=$arrayP3_5[$i]->saleqty;
    }
    else{
        $obj_3->textP3_5='';
        $obj_3->totaltextP3_5=0;
        $obj_3->totalqtyP3_5=0;
    }
    if(count($arrayP3_2)>$i && $arrayP3_2[$i]->saleqty>0){
        $obj_3->textP3_2=number_format($arrayP3_2[$i]->withvatprice, 2).' X '.$arrayP3_2[$i]->saleqty.' = '.number_format($arrayP3_2[$i]->totalamount, 2);
        $obj_3->totaltextP3_2=$arrayP3_2[$i]->totalamount;
        $obj_3->totalqtyP3_2=$arrayP3_2[$i]->saleqty;
    }
    else{
        $obj_3->textP3_2='';
        $obj_3->totaltextP3_2=0;
        $obj_3->totalqtyP3_2=0;
    }

    array_push($mainarray3, $obj_3);
}
//Empty Qty Information
$mainarray4=array();
for($i=0; $maxcountP4>$i; $i++){
    $obj_4=new stdClass();
    if(count($arrayP4_125)>$i && $arrayP4_125[$i]->saleqty>0){
        $obj_4->textP4_125=number_format($arrayP4_125[$i]->withvatprice, 2).' X '.$arrayP4_125[$i]->saleqty.' = '.number_format($arrayP4_125[$i]->totalamount, 2);
        $obj_4->totaltextP4_125=$arrayP4_125[$i]->totalamount;
        $obj_4->totalqtyP4_125=$arrayP4_125[$i]->saleqty;
    }
    else{
        $obj_4->textP4_125='';
        $obj_4->totaltextP4_125=0;
        $obj_4->totalqtyP4_125=0;
    }
    if(count($arrayP4_375)>$i && $arrayP4_375[$i]->saleqty>0){
        $obj_4->textP4_375=number_format($arrayP4_375[$i]->withvatprice, 2).' X '.$arrayP4_375[$i]->saleqty.' = '.number_format($arrayP4_375[$i]->totalamount, 2);
        $obj_4->totaltextP4_375=$arrayP4_375[$i]->totalamount;
        $obj_4->totalqtyP4_375=$arrayP4_375[$i]->saleqty;
    }
    else{
        $obj_4->textP4_375='';
        $obj_4->totaltextP4_375=0;
        $obj_4->totalqtyP4_375=0;
    }
    if(count($arrayP4_5)>$i && $arrayP4_5[$i]->saleqty>0){
        $obj_4->textP4_5=number_format($arrayP4_5[$i]->withvatprice, 2).' X '.$arrayP4_5[$i]->saleqty.' = '.number_format($arrayP4_5[$i]->totalamount, 2);
        $obj_4->totaltextP4_5=$arrayP4_5[$i]->totalamount;
        $obj_4->totalqtyP4_5=$arrayP4_5[$i]->saleqty;
    }
    else{
        $obj_4->textP4_5='';
        $obj_4->totaltextP4_5=0;
        $obj_4->totalqtyP4_5=0;
    }
    if(count($arrayP4_2)>$i && $arrayP4_2[$i]->saleqty>0){
        $obj_4->textP4_2=number_format($arrayP4_2[$i]->withvatprice, 2).' X '.$arrayP4_2[$i]->saleqty.' = '.number_format($arrayP4_2[$i]->totalamount, 2);
        $obj_4->totaltextP4_2=$arrayP4_2[$i]->totalamount;
        $obj_4->totalqtyP4_2=$arrayP4_2[$i]->saleqty;
    }
    else{
        $obj_4->textP4_2='';
        $obj_4->totaltextP4_2=0;
        $obj_4->totalqtyP4_2=0;
    }

    array_push($mainarray4, $obj_4);
}

$new2total=0;
$refil2total=0;
$empty2total=0;
$trust2total=0;
$new5total=0;
$refil5total=0;
$empty5total=0;
$trust5total=0;
$new125total=0;
$refil125total=0;
$empty125total=0;
$trust125total=0;
$new375total=0;
$refil375total=0;
$empty375total=0;
$trust375total=0;
$totalcash=0;
$totalcheque=0;
$totaltrf=0;
$totalcredit=0;
$totaldiscount=0;

$excesstotal=0;
$chequeexcess=0;
$cashexcess=0;
$banktrfexcess=0;

$creditbrekup=array();
$chequebrekup=array();
$trfbrekup=array();
$excessbrekup=array();
$discountbrekup=array();

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$vatamount = $rowvat['vat'];

$sqldaily="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_customer`.`name` AS `cusname`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`discount_status`, `tbl_employee`.`name` AS `refname`, `tbl_vehicle`.`vehicleno`, `tbl_area`.`area`, `tbl_invoice`.`paymentcomplete` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_vehicle_load`.`lorryid` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1";
$resultdaily =$conn-> query($sqldaily);
if($resultdaily->num_rows>0){
    while($rowdaily = $resultdaily-> fetch_assoc()){ 
        $invoiceID=$rowdaily['idtbl_invoice'];
        $discountstatus=$rowdaily['discount_status'];

        $sqlinvdetail="SELECT `refillqty`, `encustomer_refillprice`, `tbl_product_idtbl_product`, `discount_price` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID' AND `status`=1 AND `tbl_product_idtbl_product`=1";
        $resultinvdetail = $conn->query($sqlinvdetail);
        $rowinvdetail = $resultinvdetail->fetch_assoc();

        if(!empty($rowinvdetail['tbl_product_idtbl_product']) && $rowinvdetail['discount_price']>0){
            $refillqty=$rowinvdetail['refillqty'];
            $refill_price=(($rowinvdetail['encustomer_refillprice']*($vatamount+100))/100);
            $discount_price=(($rowinvdetail['discount_price']*($vatamount+100))/100);

            $total_refillprice=$refill_price*$refillqty;
            $total_discountprice=$discount_price*$refillqty;

            $discount_amount=$total_refillprice-$total_discountprice;

            $objdiscount=new stdClass();
            $objdiscount->customername=$rowdaily['cusname'];
            $objdiscount->invoiceno=$rowdaily['idtbl_invoice'];
            $objdiscount->tax_invoice_num=$rowdaily['tax_invoice_num'];
            $objdiscount->discountamount=$discount_amount;

            array_push($discountbrekup, $objdiscount);
        }
        else{
            $discount_amount=0;
        }   

        $sqlcash="SELECT SUM(`amount`) AS `amount` FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=1 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID')";
        $resultcash =$conn-> query($sqlcash);   
        $rowcash = $resultcash-> fetch_assoc();

        // $chequelist='';
        $chequetotal=0;
        $i=1;
        $sqlcheque="SELECT SUM(`tbl_invoice_payment_detail`.`amount`) AS `amount`, GROUP_CONCAT(`tbl_invoice_payment_detail`.`chequeno`) AS `chequeno`, `tbl_invoice_payment_detail`.`chequedate`, `tbl_bank`.`bankname` FROM `tbl_invoice_payment_detail` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_invoice_payment_detail`.`tbl_bank_idtbl_bank` WHERE `tbl_invoice_payment_detail`.`status`=1 AND `tbl_invoice_payment_detail`.`method`=2 AND `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID') GROUP BY `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`";
        $resultcheque =$conn-> query($sqlcheque);   
        while($rowcheque = $resultcheque-> fetch_assoc()){
            // $chequelist.=$rowcheque['chequeno'];
            // if($i<$resultcheque->num_rows){
            //     $chequelist.='/';
            // }

            $objcheque=new stdClass();
            $objcheque->customername=$rowdaily['cusname'];
            $objcheque->invoiceno=$rowdaily['idtbl_invoice'];
            $objcheque->tax_invoice_num=$rowdaily['tax_invoice_num'];
            $objcheque->nettotal=$rowdaily['nettotal'];
            $objcheque->chequeno=$rowcheque['chequeno'];
            $objcheque->chequedate=$rowcheque['chequedate'];
            $objcheque->bankname=$rowcheque['bankname'];
            $objcheque->amount=$rowcheque['amount'];

            array_push($chequebrekup, $objcheque);

            $chequetotal=$chequetotal+$rowcheque['amount'];
            $i++;
        }

        $banktrflist='';
        $banktrftotal=0;
        $i=1;
        $sqlbanktrf="SELECT SUM(`tbl_invoice_payment_detail`.`amount`) AS `amount`, `tbl_invoice_payment_detail`.`receiptno`, `tbl_invoice_payment_detail`.`chequedate`, `tbl_bank`.`bankname` FROM `tbl_invoice_payment_detail` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_invoice_payment_detail`.`tbl_bank_idtbl_bank` WHERE `tbl_invoice_payment_detail`.`status`=1 AND `tbl_invoice_payment_detail`.`method`=3 AND `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID') GROUP BY `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`";
        $resultbanktrf =$conn-> query($sqlbanktrf);   
        while($rowbanktrf = $resultbanktrf-> fetch_assoc()){
            $banktrflist.=$rowbanktrf['receiptno'];
            if($i<$resultbanktrf->num_rows){
                $banktrflist.='/';
            }

            $objcheque=new stdClass();
            $objcheque->customername=$rowdaily['cusname'];
            $objcheque->invoiceno=$rowdaily['idtbl_invoice'];
            $objcheque->tax_invoice_num=$rowdaily['tax_invoice_num'];
            $objcheque->nettotal=$rowdaily['nettotal'];
            $objcheque->receiptno=$rowbanktrf['receiptno'];
            $objcheque->trfdate=$rowbanktrf['chequedate'];
            $objcheque->bankname=$rowbanktrf['bankname'];
            $objcheque->amount=$rowbanktrf['amount'];

            array_push($trfbrekup, $objcheque);

            $banktrftotal+=$rowbanktrf['amount'];
            $i++;
        }

        if ($discountstatus==1) {
            $discountamount=$discount_amount;
        }
        else{
            $discountamount=0;
        }
        
        $totalcash+=$rowcash['amount'];
        $totalcheque += $chequetotal;
        $totaltrf += $banktrftotal;
        $totaldiscount += $discount_amount;

        if($rowcash['amount']>0 && $chequetotal>0 && $banktrftotal>0){
            if(($chequetotal+$rowcash['amount']+$banktrftotal)>$rowdaily['nettotal']){
                $banktrfexcess+=(($chequetotal+$rowcash['amount']+$banktrftotal)-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=(($chequetotal+$rowcash['amount']+$banktrftotal)-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($rowcash['amount']>0 && $chequetotal>0){
            if(($chequetotal+$rowcash['amount'])>$rowdaily['nettotal']){
                $chequeexcess+=(($chequetotal+$rowcash['amount'])-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=(($chequetotal+$rowcash['amount'])-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($rowcash['amount']>0 && $banktrftotal>0){
            if(($rowcash['amount']+$banktrftotal)>$rowdaily['nettotal']){
                $banktrfexcess+=(($rowcash['amount']+$banktrftotal)-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=(($rowcash['amount']+$banktrftotal)-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($chequetotal>0 && $banktrftotal>0){
            if(($chequetotal+$banktrftotal)>$rowdaily['nettotal']){
                $banktrfexcess+=(($chequetotal+$banktrftotal)-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=(($chequetotal+$banktrftotal)-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($rowcash['amount']>0){
            if($rowcash['amount']>$rowdaily['nettotal']){
                $cashexcess+=($rowcash['amount']-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cash';
                $objexcess->excessamount=($rowcash['amount']-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($chequetotal>0){
            if($chequetotal>$rowdaily['nettotal']){
                $chequeexcess+=($chequetotal-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=($chequetotal-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }
        else if($banktrftotal>0){
            if($banktrftotal>$rowdaily['nettotal']){
                $banktrfexcess+=($banktrftotal-$rowdaily['nettotal']);
    
                $objexcess=new stdClass();
                $objexcess->customername=$rowdaily['cusname'];
                $objexcess->invoiceno=$rowdaily['idtbl_invoice'];
                $objexcess->excesstype='Cheque';
                $objexcess->excessamount=($banktrftotal-$rowdaily['nettotal']);
    
                array_push($excessbrekup, $objexcess);
            }
        }

        $creditValue = $rowdaily['nettotal'] - ($discount_amount + $rowcash['amount'] + $chequetotal+$banktrftotal);
        $totalcredit += $creditValue;

        // if($creditValue>0){
        //     echo $rowdaily['cusname'].'-->'.number_format($creditValue, 2).'-->'.$rowdaily['idtbl_invoice'].'<br>';
        // }

        if($creditValue<0){
            $excesstotal+=$creditValue;
        }

        if($rowdaily['paymentcomplete']==0 && $creditValue>0){
            $objcredit=new stdClass();
            $objcredit->customername=$rowdaily['cusname'];
            $objcredit->invoiceno=$rowdaily['idtbl_invoice'];
            $objcredit->tax_invoice_num=$rowdaily['tax_invoice_num'];
            $objcredit->creditamount=$creditValue;

            array_push($creditbrekup, $objcredit);
        }


    }
}

$sqlacceinfo="SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `totqty`, ((`tbl_invoice_detail`.`newprice`*($vatamount+100))/100) AS `newprice`, (SUM(`tbl_invoice_detail`.`newqty`)*((`tbl_invoice_detail`.`newprice`*($vatamount+100))/100)) AS `netaccetotal`, `tbl_product`.`product_name` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_product`.`tbl_product_category_idtbl_product_category`=2 AND `tbl_product`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`newprice` 
UNION ALL 
SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `totqty`, ((`tbl_invoice_detail`.`encustomer_newprice`*($vatamount+100))/100) AS `newprice`, (SUM(`tbl_invoice_detail`.`newqty`)*((`tbl_invoice_detail`.`encustomer_newprice`*($vatamount+100))/100)) AS `netaccetotal`, `tbl_product`.`product_name` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_product`.`tbl_product_category_idtbl_product_category`=2 AND `tbl_product`.`status`=1 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`encustomer_newprice`>0 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_invoice_detail`.`encustomer_newprice`";
$resultacceinfo =$conn-> query($sqlacceinfo);   

// print_r($discountbrekup);
// SELECT * FROM (SELECT SUM(`tbl_invoice_payment_detail`.`amount`) AS `cashtotal` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_invoice_payment_detail` ON `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` WHERE `tbl_invoice`.`date`='2024-06-11' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_payment_detail`.`status`=1 AND `tbl_invoice_payment_detail`.`method`=1) AS `dcash` JOIN (SELECT SUM(`tbl_invoice_payment_detail`.`amount`) AS `chequetotal` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_invoice_payment_detail` ON `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` WHERE `tbl_invoice`.`date`='2024-06-11' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_payment_detail`.`status`=1 AND `tbl_invoice_payment_detail`.`method`=2) AS `dcheque` JOIN (SELECT SUM(`tbl_invoice_payment_detail`.`amount`) AS `trftotal` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` LEFT JOIN `tbl_invoice_payment_detail` ON `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` WHERE `tbl_invoice`.`date`='2024-06-11' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_payment_detail`.`status`=1 AND `tbl_invoice_payment_detail`.`method`=3) AS `dtrf` JOIN (SELECT SUM(`tbl_invoice`.`nettotal`)-SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `credit` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`date`='2024-06-11' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0) AS `dcredit`
?>
<!-- <div class="table-container">
    <table class="table table-striped table-bordered sticky-header table-sm" id="table_content">
        <thead class="thead-dark">
            <tr>
                <?php foreach($arrayproduct as $rowproductlist){ ?>
                <th><?php echo $rowproductlist->product_name ?></th>
                <?php } ?>
                <th>Accessories</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($mainarray)){ ?>
            <tr>
                <td class="text-dark font-weight-bold" colspan="6">Refill Cylinders</td> 
            </tr>
            <?php foreach($mainarray as $rowmaindata){if($rowmaindata->text2!='' | $rowmaindata->text5!='' | $rowmaindata->text125!='' | $rowmaindata->text375!=''){ ?>
            <tr>
                <td><?php echo $rowmaindata->text2 ?></td>
                <td><?php echo $rowmaindata->text5 ?></td>
                <td><?php echo $rowmaindata->text125 ?></td>
                <td><?php echo $rowmaindata->text375 ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php }} ?>
            <tr>
                <th class="text-right">
                    <?php 
                    $totalSum2=0;
                    foreach ($mainarray as $itemtotal) { 
                        if($itemtotal->text2!=''){ 
                            $totalSum2 += $itemtotal->totaltext2;
                        }
                    }
                    if($totalSum2>0){
                        echo number_format($totalSum2, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum5=0;
                    foreach ($mainarray as $itemtotal) { 
                        if($itemtotal->text5!=''){ 
                            $totalSum5 += $itemtotal->totaltext5;
                        }
                    }
                    if($totalSum5>0){
                        echo number_format($totalSum5, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum125=0;
                    foreach ($mainarray as $itemtotal) { 
                        if($itemtotal->text125!=''){ 
                            $totalSum125 += $itemtotal->totaltext125;
                        }
                    }
                    if($totalSum125>0){
                        echo number_format($totalSum125, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum375=0;
                    foreach ($mainarray as $itemtotal) { 
                        if($itemtotal->text375!=''){ 
                            $totalSum375 += $itemtotal->totaltext375;
                        }
                    }
                    if($totalSum375>0){
                        echo number_format($totalSum375, 2);
                    }
                    ?>        
                </th>
                <th></th>
                <th></th>
            </tr>
            <?php } if(!empty($mainarray2)){ ?>
            <tr>
                <td class="text-dark font-weight-bold" colspan="6">New Cylinders</td> 
            </tr>
            <?php foreach($mainarray2 as $rowmaindata2){if($rowmaindata2->textP2_2!='' | $rowmaindata2->textP2_5!='' | $rowmaindata2->textP2_125!='' | $rowmaindata2->textP2_375!=''){ ?>
            <tr>
                <td><?php echo $rowmaindata2->textP2_2 ?></td>
                <td><?php echo $rowmaindata2->textP2_5 ?></td>
                <td><?php echo $rowmaindata2->textP2_125 ?></td>
                <td><?php echo $rowmaindata2->textP2_375 ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php }} ?>
            <tr>
                <th class="text-right">
                    <?php 
                    $totalSum2P2=0;
                    foreach ($mainarray2 as $itemtotalP2) { 
                        if($itemtotalP2->textP2_2!=''){ 
                            $totalSum2P2 += $itemtotalP2->totaltextP2_2;
                        }
                    }
                    if($totalSum2P2>0){
                        echo number_format($totalSum2P2, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum5P2=0;
                    foreach ($mainarray2 as $itemtotalP2) { 
                        if($itemtotalP2->textP2_5!=''){ 
                            $totalSum5P2 += $itemtotalP2->totaltextP2_5;
                        }
                    }
                    if($totalSum5P2>0){
                        echo number_format($totalSum5P2, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum125P2=0;
                    foreach ($mainarray2 as $itemtotalP2) { 
                        if($itemtotalP2->textP2_125!=''){ 
                            $totalSum125P2 += $itemtotalP2->totaltextP2_125;
                        }
                    }
                    if($totalSum125P2>0){
                        echo number_format($totalSum125P2, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum375P2=0;
                    foreach ($mainarray2 as $itemtotalP2) { 
                        if($itemtotalP2->textP2_375!=''){ 
                            $totalSum375P2 += $itemtotalP2->totaltextP2_375;
                        }
                    }
                    if($totalSum375P2>0){
                        echo number_format($totalSum375P2, 2);
                    }
                    ?>        
                </th>
                <th></th>
                <th></th>
            </tr>
            <?php } if(!empty($mainarray3)){ ?>
            <tr>
                <td class="text-dark font-weight-bold" colspan="6">Trust Cylinders</td> 
            </tr>
            <?php foreach($mainarray3 as $rowmaindata3){if($rowmaindata3->textP3_2!='' | $rowmaindata3->textP3_5!='' | $rowmaindata3->textP3_125!='' | $rowmaindata3->textP3_375!=''){ ?>
            <tr>
                <td><?php echo $rowmaindata3->textP3_2 ?></td>
                <td><?php echo $rowmaindata3->textP3_5 ?></td>
                <td><?php echo $rowmaindata3->textP3_125 ?></td>
                <td><?php echo $rowmaindata3->textP3_375 ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php }} ?>
            <tr>
                <th class="text-right">
                    <?php 
                    $totalSum2P3=0;
                    foreach ($mainarray3 as $itemtotalP3) { 
                        if($itemtotalP3->textP3_2!=''){ 
                            $totalSum2P3 += $itemtotalP3->totaltextP3_2;
                        }
                    }
                    if($totalSum2P3>0){
                        echo number_format($totalSum2P3, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum5P3=0;
                    foreach ($mainarray3 as $itemtotalP3) { 
                        if($itemtotalP3->textP3_5!=''){ 
                            $totalSum5P3 += $itemtotalP3->totaltextP3_5;
                        }
                    }
                    if($totalSum5P3>0){
                        echo number_format($totalSum5P3, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum125P3=0;
                    foreach ($mainarray3 as $itemtotalP3) { 
                        if($itemtotalP3->textP3_125!=''){ 
                            $totalSum125P3 += $itemtotalP3->totaltextP3_125;
                        }
                    }
                    if($totalSum125P3>0){
                        echo number_format($totalSum125P3, 2);
                    }
                    ?>        
                </th>
                <th class="text-right">
                    <?php 
                    $totalSum375P3=0;
                    foreach ($mainarray3 as $itemtotalP3) { 
                        if($itemtotalP3->textP3_375!=''){ 
                            $totalSum375P3 += $itemtotalP3->totaltextP3_375;
                        }
                    }
                    if($totalSum375P3>0){
                        echo number_format($totalSum375P3, 2);
                    }
                    ?>        
                </th>
                <th></th>
                <th></th>
            </tr>
            <?php } ?>
            <tr>
                <th colspan="5">Total</th>
                <th class="text-right"><?php echo number_format($nettotal, 2) ?></th>
            </tr>
        </tbody>
    </table>
</div> -->
<div class="table-container">
    <table class="table table-striped table-bordered sticky-header table-sm small" id="table_content">
        <tbody>
            <tr>
                <td><?php echo 'Sale '.date("d.m.Y", strtotime($validfrom)); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
            $total2qty=0;
            $total5qty=0;
            $total125qty=0;
            $total375qty=0;

            $refilqty=0;
            $refilqty2=0;
            $refilqty5=0;
            $refilqty125=0;
            $refilqty375=0;

            $newqty=0;
            $newqty2=0;
            $newqty5=0;
            $newqty125=0;
            $newqty375=0;

            $emptyqty=0;
            $emptyqty2=0;
            $emptyqty5=0;
            $emptyqty125=0;
            $emptyqty375=0;

            $trustqty=0;
            $trustqty2=0;
            $trustqty5=0;
            $trustqty125=0;
            $trustqty375=0;

            $nettotalacce=0;

            for($k=0; $k<$max_value; $k++){ 
                if(!empty($mainarray2[$k]->textP2_2)){$total2qty+=$mainarray2[$k]->totalqtyP2_2; $newqty+=$mainarray2[$k]->totalqtyP2_2; $newqty2+=$mainarray[$k]->totalqtyP2_2;}
                if(!empty($mainarray[$k]->text2)){$total2qty+=$mainarray[$k]->totalqty2; $refilqty+=$mainarray[$k]->totalqty2; $refilqty2+=$mainarray[$k]->totalqty2;}
                if(!empty($mainarray3[$k]->textP3_2)){$total2qty+=$mainarray3[$k]->totalqtyP3_2; $trustqty+=$mainarray3[$k]->totalqtyP3_2; $trustqty2+=$mainarray3[$k]->totalqtyP3_2;}
                if(!empty($mainarray4[$k]->textP4_2)){$total2qty+=$mainarray4[$k]->totalqtyP4_2; $emptyqty+=$mainarray4[$k]->totalqtyP4_2; $emptyqty2+=$mainarray4[$k]->totalqtyP4_2;}

                if(!empty($mainarray2[$k]->textP2_5)){$total5qty+=$mainarray2[$k]->totalqtyP2_5; $newqty+=$mainarray2[$k]->totalqtyP2_5; $newqty5+=$mainarray2[$k]->totalqtyP2_5;}
                if(!empty($mainarray[$k]->text5)){$total5qty+=$mainarray[$k]->totalqty5; $refilqty+=$mainarray[$k]->totalqty5; $refilqty5+=$mainarray[$k]->totalqty5;}
                if(!empty($mainarray3[$k]->textP3_5)){$total5qty+=$mainarray3[$k]->totalqtyP3_5; $trustqty+=$mainarray3[$k]->totalqtyP3_5; $trustqty5+=$mainarray3[$k]->totalqtyP3_5;}
                if(!empty($mainarray4[$k]->textP4_5)){$total5qty+=$mainarray4[$k]->totalqtyP4_5; $emptyqty+=$mainarray4[$k]->totalqtyP4_5; $emptyqty5+=$mainarray4[$k]->totalqtyP4_5;}

                if(!empty($mainarray2[$k]->textP2_125)){$total125qty+=$mainarray2[$k]->totalqtyP2_125; $newqty+=$mainarray2[$k]->totalqtyP2_125; $newqty125+=$mainarray2[$k]->totalqtyP2_125;}
                if(!empty($mainarray[$k]->text125)){$total125qty+=$mainarray[$k]->totalqty125; $refilqty+=$mainarray[$k]->totalqty125; $refilqty125+=$mainarray[$k]->totalqty125;}
                if(!empty($mainarray3[$k]->textP3_125)){$total125qty+=$mainarray3[$k]->totalqtyP3_125; $trustqty+=$mainarray3[$k]->totalqtyP3_125; $trustqty125+=$mainarray3[$k]->totalqtyP3_125;}
                if(!empty($mainarray4[$k]->textP4_125)){$total125qty+=$mainarray4[$k]->totalqtyP4_125; $emptyqty+=$mainarray4[$k]->totalqtyP4_125; $emptyqty125+=$mainarray4[$k]->totalqtyP4_125;}

                if(!empty($mainarray2[$k]->textP2_375)){$total375qty+=$mainarray2[$k]->totalqtyP2_375; $newqty+=$mainarray2[$k]->totalqtyP2_375; $newqty375+=$mainarray2[$k]->totalqtyP2_375;}
                if(!empty($mainarray[$k]->text375)){$total375qty+=$mainarray[$k]->totalqty375; $refilqty+=$mainarray[$k]->totalqty375; $refilqty375+=$mainarray[$k]->totalqty375;}
                if(!empty($mainarray3[$k]->textP3_375)){$total375qty+=$mainarray3[$k]->totalqtyP3_375; $trustqty+=$mainarray3[$k]->totalqtyP3_375; $trustqty375+=$mainarray3[$k]->totalqtyP3_375;}
                if(!empty($mainarray4[$k]->textP4_375)){$total375qty+=$mainarray4[$k]->totalqtyP4_375; $emptyqty+=$mainarray4[$k]->totalqtyP4_375; $emptyqty375+=$mainarray4[$k]->totalqtyP4_375;}
            }
            if($refilqty>0){ 
            ?>
            <tr>
                <?php foreach($arrayproduct as $rowproductlist){ ?>
                <th nowrap class="text-center table-dark"><?php echo $rowproductlist->product_name ?></th>
                <?php } ?>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
            </tr>
            <!-- <tr>
                <th colspan="6">Refil Cylinders</th>
            </tr> -->
            <tr>
                <td class="text-center"><?php if($refilqty2>0){echo $refilqty2;} ?></td>
                <td class="text-center"><?php if($refilqty5>0){echo $refilqty5;} ?></td>
                <td class="text-center"><?php if($refilqty125>0){echo $refilqty125;} ?></td>
                <td class="text-center"><?php if($refilqty375>0){echo $refilqty375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php for($k=0; $k<$max_value; $k++){ ?>
            <tr>
                <td nowrap><?php if(!empty($mainarray[$k]->text2)){echo $mainarray[$k]->text2; $refil2total+=$mainarray[$k]->totaltext2;} ?></td>
                <td nowrap><?php if(!empty($mainarray[$k]->text5)){echo $mainarray[$k]->text5; $refil5total+=$mainarray[$k]->totaltext5;} ?></td>
                <td nowrap><?php if(!empty($mainarray[$k]->text125)){echo $mainarray[$k]->text125; $refil125total+=$mainarray[$k]->totaltext125;} ?></td>
                <td nowrap><?php if(!empty($mainarray[$k]->text375)){echo $mainarray[$k]->text375; $refil375total+=$mainarray[$k]->totaltext375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
            <tr>
                <td nowrap class="text-right"><?php if($refil2total>0){echo number_format($refil2total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($refil5total>0){echo number_format($refil5total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($refil125total>0){echo number_format($refil125total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($refil375total>0){echo number_format($refil375total, 2);} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } if($resultacceinfo->num_rows>0){ ?> 
            <tr>
                <th nowrap class="table-dark">Accessories</th>
                <th nowrap class="table-dark">Quantity</th>
                <td nowrap class="table-dark"></td>
                <td nowrap class="table-dark"></td>
                <td nowrap class="table-dark"></td>
                <td nowrap class="table-dark"></td>
                <td nowrap class="table-dark"></td>
            </tr>    
            <?php while($rowacceinfo = $resultacceinfo-> fetch_assoc()){ ?> 
            <tr>
                <td nowrap><?php echo $rowacceinfo['product_name'] ?></td>
                <td nowrap><?php echo $rowacceinfo['totqty'] ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowacceinfo['newprice'], 2).' X '.$rowacceinfo['totqty'].' = '.number_format($rowacceinfo['netaccetotal'], 2); $nettotalacce+=$rowacceinfo['netaccetotal']; ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?> 
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th nowrap class="text-right"><?php echo number_format($nettotalacce, 2) ?></th>
                <th></th>
            </tr>
            <?php } if($newqty>0){ ?>
            <tr>
                <th colspan="7">New Cylinders</th>
            </tr>
            <tr>
                <?php foreach($arrayproduct as $rowproductlist){ ?>
                <th nowrap class="text-center table-dark"><?php echo $rowproductlist->product_name ?></th>
                <?php } ?>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
            </tr>
            <tr>
                <td class="text-center"><?php if($newqty2>0){echo $newqty2;} ?></td>
                <td class="text-center"><?php if($newqty5>0){echo $newqty5;} ?></td>
                <td class="text-center"><?php if($newqty125>0){echo $newqty125;} ?></td>
                <td class="text-center"><?php if($newqty375>0){echo $newqty375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php for($k=0; $k<$max_value; $k++){ ?>
            <tr>
                <td nowrap><?php if(!empty($mainarray2[$k]->textP2_2)){echo $mainarray2[$k]->textP2_2; $new2total+=$mainarray2[$k]->totaltextP2_2;} ?></td>
                <td nowrap><?php if(!empty($mainarray2[$k]->textP2_5)){echo $mainarray2[$k]->textP2_5; $new5total+=$mainarray2[$k]->totaltextP2_5;} ?></td>
                <td nowrap><?php if(!empty($mainarray2[$k]->textP2_125)){echo $mainarray2[$k]->textP2_125; $new125total+=$mainarray2[$k]->totaltextP2_125;} ?></td>
                <td nowrap><?php if(!empty($mainarray2[$k]->textP2_375)){echo $mainarray2[$k]->textP2_375; $new375total+=$mainarray2[$k]->totaltextP2_375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?> 
            <tr>
                <td nowrap class="text-right"><?php if($new2total>0){echo number_format($new2total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($new5total>0){echo number_format($new5total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($new125total>0){echo number_format($new125total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($new375total>0){echo number_format($new375total, 2);} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php }if($emptyqty>0){ ?>
            <tr>
                <th colspan="6">Empty Cylinders</th>
            </tr>
            <tr>
                <?php foreach($arrayproduct as $rowproductlist){ ?>
                <th nowrap class="text-center table-dark"><?php echo $rowproductlist->product_name ?></th>
                <?php } ?>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
            </tr>
            <tr>
                <td class="text-center"><?php if($emptyqty2>0){echo $emptyqty2;} ?></td>
                <td class="text-center"><?php if($emptyqty5>0){echo $emptyqty5;} ?></td>
                <td class="text-center"><?php if($emptyqty125>0){echo $emptyqty125;} ?></td>
                <td class="text-center"><?php if($emptyqty375>0){echo $emptyqty375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php for($k=0; $k<$max_value; $k++){ ?>
            <tr>
                <td nowrap><?php if(!empty($mainarray4[$k]->textP4_2)){echo $mainarray4[$k]->textP4_2; $trust2total+=$mainarray4[$k]->totaltextP4_2;} ?></td>
                <td nowrap><?php if(!empty($mainarray4[$k]->textP4_5)){echo $mainarray4[$k]->textP4_5; $trust5total+=$mainarray4[$k]->totaltextP4_5;} ?></td>
                <td nowrap><?php if(!empty($mainarray4[$k]->textP4_125)){echo $mainarray4[$k]->textP4_125; $trust125total+=$mainarray4[$k]->totaltextP4_125;} ?></td>
                <td nowrap><?php if(!empty($mainarray4[$k]->textP4_375)){echo $mainarray4[$k]->textP4_375; $trust375total+=$mainarray4[$k]->totaltextP4_375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
            <tr>
                <td nowrap class="text-right"><?php if($empty2total>0){echo number_format($empty2total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($empty5total>0){echo number_format($empty5total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($empty125total>0){echo number_format($empty125total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($empty375total>0){echo number_format($empty375total, 2);} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php }if($trustqty>0){ ?>
            <tr>
                <th colspan="7">Trust Cylinders</th>
            </tr>
            <tr>
                <?php foreach($arrayproduct as $rowproductlist){ ?>
                <th nowrap class="text-center table-dark"><?php echo $rowproductlist->product_name ?></th>
                <?php } ?>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
                <th nowrap class="text-center align-top table-dark"></th>
            </tr>
            <tr>
                <td class="text-center"><?php if($trustqty2>0){echo $trustqty2;} ?></td>
                <td class="text-center"><?php if($trustqty5>0){echo $trustqty5;} ?></td>
                <td class="text-center"><?php if($trustqty125>0){echo $trustqty125;} ?></td>
                <td class="text-center"><?php if($trustqty375>0){echo $trustqty375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php for($k=0; $k<$max_value; $k++){ ?>
            <tr>
                <td nowrap><?php if(!empty($mainarray3[$k]->textP3_2)){echo $mainarray3[$k]->textP3_2; $empty2total+=$mainarray3[$k]->totaltextP3_2;} ?></td>
                <td nowrap><?php if(!empty($mainarray3[$k]->textP3_5)){echo $mainarray3[$k]->textP3_5; $empty5total+=$mainarray3[$k]->totaltextP3_5;} ?></td>
                <td nowrap><?php if(!empty($mainarray3[$k]->textP3_125)){echo $mainarray3[$k]->textP3_125; $empty125total+=$mainarray3[$k]->totaltextP3_125;} ?></td>
                <td nowrap><?php if(!empty($mainarray3[$k]->textP3_375)){echo $mainarray3[$k]->textP3_375; $empty375total+=$mainarray3[$k]->totaltextP3_375;} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?> 
            <tr>
                <td nowrap class="text-right"><?php if($trust2total>0){echo number_format($trust2total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($trust5total>0){echo number_format($trust5total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($trust125total>0){echo number_format($trust125total, 2);} ?></td>
                <td nowrap class="text-right"><?php if($trust375total>0){echo number_format($trust375total, 2);} ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
            
            <tr>
                <th nowrap class="text-right"><?php if($new2total>0 | $refil2total>0 | $empty2total>0 | $trust2total>0){echo number_format(($new2total+$refil2total+$empty2total+$trust2total), 2);} ?></th>
                <th nowrap class="text-right"><?php if($new5total>0 | $refil5total>0 | $empty5total>0 | $trust5total>0){echo number_format(($new5total+$refil5total+$empty5total+$trust5total), 2);} ?></th>
                <th nowrap class="text-right"><?php if($new125total>0 | $refil125total>0 | $empty125total>0 | $trust125total>0){echo number_format(($new125total+$refil125total+$empty125total+$trust125total), 2);} ?></th>
                <th nowrap class="text-right"><?php if($new375total>0 | $refil375total>0 | $empty375total>0 | $trust375total>0){echo number_format(($new375total+$refil375total+$empty375total+$trust375total), 2);} ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format($nettotalacce, 2) ?></th>
                <th nowrap class="text-right"><?php echo number_format($nettotal, 2) ?></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap class="text-center table-dark">Cash</th>
                <th nowrap class="text-center table-dark">Cheque</th>
                <th nowrap class="text-center table-dark">Credit</th>
                <th nowrap class="text-center table-dark">Discount</th>
                <th nowrap class="text-center table-dark">TRF</th>
                <th nowrap class="text-center table-dark">Refund</th>
                <th nowrap class="text-center table-dark"></th>
            </tr>
            <tr>
                <th nowrap class="text-right"><?php if($totalcash>0){echo number_format($totalcash-$cashexcess, 2);} ?></th>
                <th nowrap class="text-right"><?php if($totalcheque>0){echo number_format($totalcheque-$chequeexcess, 2);} ?></th>
                <th nowrap class="text-right"><?php if($totalcredit>0){echo number_format($totalcredit+($cashexcess+$chequeexcess+$banktrftotal), 2);} ?></th>
                <th nowrap class="text-right"><?php if($totaldiscount>0){echo number_format($totaldiscount, 2);} ?></th>
                <th nowrap class="text-right"><?php if($totaltrf>0){echo number_format($totaltrf-$banktrfexcess, 2);} ?></th>
                <th nowrap class="text-right">-</th>
                <th nowrap class="text-right"><?php echo number_format(($totalcash+$totalcheque+$totalcredit+$totaltrf+$totaldiscount), 2); ?></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap class="table-dark" colspan="7">Excess</th>
            </tr>
            <tr>
                <th nowrap class="text-right" colspan="7"><?php echo number_format(ABS($excesstotal), 2) ?></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap colspan="7">TRF Brekup</th>
            </tr>
            <tr>
                <th nowrap class="table-dark">Customer</th>
                <th nowrap class="table-dark">Amount</th>
                <th nowrap class="table-dark">Bill No</th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
            </tr>
            <?php $trflisttotal=0; foreach($trfbrekup as $rowtrfbrekup){ ?>
            <tr>
                <td nowrap><?php echo $rowtrfbrekup->customername ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowtrfbrekup->amount, 2); $trflisttotal+=$rowtrfbrekup->amount; ?></td>
                <td nowrap class=""><?php if(!empty($rowtrfbrekup->tax_invoice_num)){echo 'AGT'.$rowtrfbrekup->tax_invoice_num;}else{echo 'INV-'.$rowtrfbrekup->invoiceno;} ?></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-center"></td>
                <td nowrap class="text-center"></td>
            </tr>
            <?php } ?>
            <tr>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format($trflisttotal, 2) ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-center"></th>
                <th nowrap class="text-center"></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap colspan="7">Discount Brekup</th>
            </tr>
            <tr>
                <th nowrap class="table-dark">Customer</th>
                <th nowrap class="table-dark">Amount</th>
                <th nowrap class="table-dark">Bill No</th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
            </tr>
            <?php $trflisttotal=0; foreach($discountbrekup as $rowdiscountbrekup){ ?>
            <tr>
                <td nowrap><?php echo $rowdiscountbrekup->customername ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowdiscountbrekup->discountamount, 2); ?></td>
                <td nowrap class=""><?php if(!empty($rowdiscountbrekup->tax_invoice_num)){echo 'AGT'.$rowdiscountbrekup->tax_invoice_num;}else{echo 'INV-'.$rowdiscountbrekup->invoiceno;} ?></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-center"></td>
                <td nowrap class="text-center"></td>
            </tr>
            <?php } ?>
            <tr>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format($totaldiscount, 2) ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-center"></th>
                <th nowrap class="text-center"></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap colspan="7">Credit Brekup</th>
            </tr>
            <tr>
                <th nowrap class="table-dark">Customer</th>
                <th nowrap class="text-right table-dark">Amount</th>
                <th nowrap class="table-dark">Bill No</th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
            </tr>
            <?php $creditlisttotal=0; foreach($creditbrekup as $rowcreditbrekup){ ?>
            <tr>
                <td nowrap><?php echo $rowcreditbrekup->customername ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowcreditbrekup->creditamount, 2); $creditlisttotal+=$rowcreditbrekup->creditamount; ?></td>
                <td nowrap class=""><?php if(!empty($rowcreditbrekup->tax_invoice_num)){echo 'AGT'.$rowcreditbrekup->tax_invoice_num;}else{echo 'INV-'.$rowcreditbrekup->invoiceno;} ?></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-center"></td>
                <td nowrap class="text-center"></td>
            </tr>
            <?php } ?>
            <tr>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format($creditlisttotal, 2) ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-center"></th>
                <th nowrap class="text-center"></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap colspan="7">Cheque Brekup</th>
            </tr>
            <tr>
                <th nowrap class="table-dark">Customer</th>
                <th nowrap class="text-right table-dark">Amount</th>
                <th nowrap class="table-dark">Bank</th>
                <th nowrap class="table-dark">Cheque Date</th>
                <th nowrap class="table-dark">Cheque No</th>
                <th nowrap class="table-dark">Bill No</th>
                <th nowrap class="table-dark"></th>
            </tr>
            <?php $chequelisttotal=0; foreach($chequebrekup as $rowchequebrekup){ ?>
            <tr>
                <td nowrap><?php echo $rowchequebrekup->customername ?></td>
                <td nowrap class="text-right">
                <?php 
                    if($rowchequebrekup->nettotal<=$rowchequebrekup->amount){
                        echo number_format($rowchequebrekup->nettotal, 2); $chequelisttotal+=$rowchequebrekup->amount; 
                    }
                    else{
                        echo number_format($rowchequebrekup->amount, 2); $chequelisttotal+=$rowchequebrekup->amount; 
                    }
                ?>
                </td>
                <td nowrap class=""><?php echo $rowchequebrekup->bankname ?></td>
                <td nowrap class=""><?php echo $rowchequebrekup->chequedate ?></td>
                <td nowrap class=""><?php echo $rowchequebrekup->chequeno ?></td>
                <td nowrap class=""><?php if(!empty($rowchequebrekup->tax_invoice_num)){echo 'AGT'.$rowchequebrekup->tax_invoice_num;}else{echo 'INV-'.$rowchequebrekup->invoiceno;} ?></td>
                <td></td>
            </tr>
            <?php } ?>
            <tr>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format(($chequelisttotal-$chequeexcess), 2) ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-center"></th>
                <th nowrap class="text-center"></th>
            </tr>
            <tr>
                <th nowrap colspan="7"></th>
            </tr>
            <tr>
                <th nowrap colspan="7">Excess Brekup</th>
            </tr>
            <tr>
                <th nowrap class="table-dark">Customer</th>
                <th nowrap class="text-right table-dark">Amount</th>
                <th nowrap class="table-dark">Type</th>
                <th nowrap class="text-right table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
                <th nowrap class="text-center table-dark"></th>
            </tr>
            <?php $excesslisttotal=0; foreach($excessbrekup as $rowexcessbrekup){ if(number_format($rowexcessbrekup->excessamount, 2)>0){ ?>
            <tr>
                <td nowrap><?php echo $rowexcessbrekup->customername ?></td>
                <td nowrap class="text-right"><?php echo number_format($rowexcessbrekup->excessamount, 2); $excesslisttotal+=$rowexcessbrekup->excessamount; ?></td>
                <td nowrap class=""><?php echo $rowexcessbrekup->excesstype ?></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-right"></td>
                <td nowrap class="text-center"></td>
                <td nowrap class="text-center"></td>
            </tr>
            <?php }} ?>
            <tr>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"><?php echo number_format(($excesslisttotal), 2) ?></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-right"></th>
                <th nowrap class="text-center"></th>
                <th nowrap class="text-center"></th>
            </tr>
        </tbody>
    </table>
</div>