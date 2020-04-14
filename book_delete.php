<?php
include "common.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$no = $_GET['no'];

if(empty($no)){
    warning("잘못된 접근입니다.","index.php");
}

mysqli_query($conn, "DELETE FROM bookinfo WHERE no=$no;");

warning("책 정보 삭제 완료","admin.php?type=book");

?>