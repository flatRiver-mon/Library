<?php
include "common.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$mode = $_GET['mode'];

if(empty($mode)){
    warning("잘못된 접근입니다.","index.php");
}else if($mode == "level"){
    $no = $_GET['memno'];
    $level = $_GET['memlv'];
    mysqli_query($conn, "UPDATE lib_mem SET userlv=$level WHERE no=$no;");
}else if($mode == "delay"){
    $no = $_GET['memno'];
    $delay = $_GET['memdelay'];
    mysqli_query($conn, "UPDATE lib_mem SET delay=$delay WHERE no=$no;");
}

warning("회원정보 수정 완료.",-1);

?>