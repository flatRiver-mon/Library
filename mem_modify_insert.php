<?php
    
include "common.php";

$userid = $_POST['userid'];

if($userid != $_SESSION['id']){
    warning("아이디가 올바르지 않습니다.",-1);
}

$userid = txtini($userid);
$userpw = $_POST['userpw'];
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$phone3 = $_POST['phone3'];
$phone = $phone1."-".$phone2."-".$phone3;
$address = $_POST['address'];

$userpw = $_POST['userpw'];
if(!empty($userpw)){
    // 새 비밀번호가 있을때
    $userpw = password_hash($userpw, PASSWORD_DEFAULT, ['cost' => 10]);
    mysqli_query($conn, "UPDATE lib_mem SET userpw='$userpw' WHERE userid='$userid';")or die("데이터입력 오류");
}else{
    // 새 비밀번호가 없을때
}

//나머지 데이터 업데이트.(birth_year,birth_month,birth_date,phone1,phone2,phone3,bank,account)
mysqli_query($conn, "UPDATE lib_mem SET phone='$phone',address='$address' WHERE userid='$userid';");

warning("회원정보 수정이 완료됐습니다.","index.php");


?>