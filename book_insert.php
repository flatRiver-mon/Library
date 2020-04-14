<?php
include "common.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$booktitle = $_GET['booktitle'];
$author = $_GET['author'];
$publ = $_GET['publ'];
$origindate = $_GET['origindate'];
$bookclass = $_GET['bookclass'];
$isbn = $_GET['isbn'];
$loca = $_GET['loca'];

mysqli_query($conn, "INSERT INTO bookinfo (bookname, author, publ, origindate, bookclass, isbn, get_date, locate) VALUES ('$booktitle','$author','$publ','$origindate','$bookclass','$isbn','$today','$loca')");

warning("책 정보 등록 완료.","admin.php?type=book");

?>


