<?php
include "common.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$recall_mem = $_GET['recall_mem'];
$recall_book = $_GET['recall_book'];

$data = mysqli_query($conn, "SELECT no FROM rental WHERE rent_book='$recall_book' AND rent_mem='$recall_mem' AND rent_over IS NULL;");
$row = mysqli_fetch_assoc($data);
$num = $row['no'];

if(empty($num)){
    warning("존재하지 않는 기록입니다.",-1);
}

$takedata = mysqli_query($conn, "SELECT taken FROM bookinfo WHERE no='$recall_book';");
$taken = mysqli_fetch_assoc($takedata);

if($taken['taken'] == 0){
    warning("미대여 도서입니다.",-1);
}

mysqli_query($conn, "UPDATE rental SET rent_over='$today' WHERE no=$num;");
mysqli_query($conn, "UPDATE bookinfo SET taken=0 WHERE no=$recall_book;");
mysqli_query($conn, "UPDATE lib_mem SET rental_count=rental_count-1 WHERE no=$recall_mem;");

warning("도서 반납 완료",-1);

?>