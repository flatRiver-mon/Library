<?php

include "common.php";

$userno = $_GET['userno'];

if(!empty($userno)){
    $data = mysqli_query($conn, "SELECT no,rental_count FROM lib_mem WHERE no=$userno;");
    $row = mysqli_fetch_assoc($data);
    if(empty($row['no'])){
        echo -1;
    }else{
        echo $row['rental_count'];
    }
}

?>