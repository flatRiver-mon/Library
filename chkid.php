<?php
include "common.php";

$userid = $_GET['userid'];

if(!empty($userid)){
    $data = mysqli_query($conn, "SELECT no FROM lib_mem WHERE userid='$userid';");
    $len = mysqli_num_rows($data);
    echo $len;
}else{
    echo "empty";
}
?>