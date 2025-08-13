<?php
require_once('../connection/db.php');

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : null;
$groupcategory = isset($_POST['groupcategory']) ? $_POST['groupcategory'] : null;

if (!isset($searchTerm)) {
    $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
    if(isset($_POST['groupcategory'])){
        $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
    }
    $sql.=" AND `status`=1 LIMIT 5";
    $result=$conn->query($sql);
} else {
    if (!empty($searchTerm)) {
        $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
        if(isset($_POST['groupcategory'])){
            $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
        }
        $sql.=" AND `status`=1 AND `name` LIKE '%$searchTerm%'";
        $result=$conn->query($sql);
    } else {
        $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
        if(isset($_POST['groupcategory'])){
            $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
        }
        $sql.=" AND `status`=1 LIMIT 5";
        $result=$conn->query($sql);
    }
}

while($row=$result->fetch_assoc()){
    $data[]=array("id"=>$row['idtbl_customer'], "text"=>$row['name']);
}
echo json_encode($data);
?>
