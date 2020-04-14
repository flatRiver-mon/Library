<?php
include "common.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$no = $_GET['no'];
$booktitle = $_GET['booktitle'];
$author = $_GET['author'];
$publ = $_GET['publ'];
$origindate = $_GET['origindate'];
$bookclass = $_GET['bookclass'];
$isbn = $_GET['isbn'];
$loca = $_GET['loca'];
$avail = $_GET['avail'];

mysqli_query($conn, "UPDATE bookinfo SET bookname='$booktitle',author='$author',publ='$publ',origindate='$origindate',bookclass='$bookclass',isbn='$isbn',locate='$loca',avail='$avail' WHERE no=$no");

warning("책 정보 수정 완료.","admin.php?type=book");

?>


