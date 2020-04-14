<?php
include "common.php";

$userid = txtini($_POST['userid']);
$userpw = $_POST['userpw'];
$userpw = password_hash($userpw, PASSWORD_DEFAULT,['cost'=> 10]);
$username = $_POST['username'];
$resi_num1 = $_POST['resi_num1'];
$resi_num2 = $_POST['resi_num2'];
$resi_num = $resi_num1."-".$resi_num2;
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$phone3 = $_POST['phone3'];
$phone = $phone1."-".$phone2."-".$phone3;
$address = $_POST['address'];

mysqli_query($conn, "INSERT INTO lib_mem (userid,userpw,username,resi_num,phone,address,date,userlv) VALUES ('$userid','$userpw','$username','$resi_num','$phone','$address','$today',1);");

warning("회원가입이 완료되었습니다. 로그인해 주세요.","index.php");

?>