<?php
include "common.php";

cutprevdata();

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$rent_mem = $_POST['rent_mem'];
$rent_book = $_POST['rent_book'];

$memdata = mysqli_query($conn, "SELECT * FROM lib_mem WHERE no=$rent_mem;");
$mem = mysqli_fetch_assoc($memdata);

if(empty($mem['no'])){
    warning("해당 회원이 존재하지 않습니다.",-1);    
}

$bookdata = mysqli_query($conn, "SELECT * FROM bookinfo WHERE no=$rent_book;");
$book = mysqli_fetch_assoc($bookdata);

if(empty($book['no'])){
    warning("해당 도서가 존재하지 않습니다.",-1);
}

if($book['avail'] == 1){
    warning("해당 도서가 이용제한중 입니다.",-1);
}elseif($book['taken'] == 1){
    warning("해당 도서가 대여중 입니다.",-1);
}

if($mem['rental_count'] >= 5){
    warning("해당 회원이 대여 최대수를 초과했습니다.",-1);
}elseif($mem['delay'] == 1){
    warning("해당 회원이 연체자 입니다.",-1);
}

$dday = time();
$sevenplus = date("Y-m-d",strtotime("+7 days",$dday));
mysqli_query($conn, "INSERT INTO rental (rent_book,rent_mem,rent_date,rent_due) VALUES ('$rent_book','$rent_mem','$today','$sevenplus');");
mysqli_query($conn, "UPDATE lib_mem SET rental_count=rental_count+1, accum_count=accum_count+1 WHERE no=$rent_mem;");
mysqli_query($conn, "UPDATE bookinfo SET accum_count=accum_count+1, taken=1 WHERE no=$rent_book;");




    
warning("도서 대여완료",-1);
    
?>