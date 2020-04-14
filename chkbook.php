<?php
include "common.php";

$bookno = $_GET['bookno'];

if(!empty($bookno)){
    $data = mysqli_query($conn, "SELECT no,avail,taken FROM bookinfo WHERE no=$bookno;");
    $row = mysqli_fetch_assoc($data);
    if($row['avail'] == 1){
        echo 2;
    }else if(empty($row['no'])){
        echo 3;
    }else{
        echo $row['taken'];
    }
}
?>