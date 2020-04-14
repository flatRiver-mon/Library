<?php
header("Content-Type:text/html; charset=UTF-8;");

include "secret.php";
//sql 접속
$conn   = mysqli_connect("localhost","$id","$password") or die("sql접속이 실패했습니다.");
$select = mysqli_select_db($conn, "$id") or die("DB 선택 에러");

mysqli_query($conn,"SET SESSION CHARACTER_SET_CONNECTION=utf8");
mysqli_query($conn,"SET SESSION CHARACTER_SET_RESULTS=utf8");
mysqli_query($conn,"SET SESSION CHARACTER_SET_CLIENT=utf8");
session_start();

if(!isset($_SESSION['userlv'])){
    $_SESSION['userlv'] = 0;
}

// 한 페이지 당 보여줄 게시물 개수
$postlen = 10;

// 한 블럭당 넣을 페이지번호의 개수
$pbtnlen = 5;

// 사용자 함수

function warning($message, $move){
    echo "<script>";
    echo "alert('$message');";
    if(is_numeric($move)){
        // 숫자라면
        echo "history.go($move);";
    }else{
        // 숫자가 아니라면
        echo "location.href='$move';";
    }
    echo "</script>";
    exit;
}

function txtini($text){
    $text = addslashes($text);
    $text = htmlentities($text);
    return $text;
}

function id2name($userid){
    $conn = $GLOBALS["conn"];
    $userdata = mysqli_query($conn, "SELECT username FROM lib_mem WHERE userid='$userid';");
    $datarow = mysqli_fetch_assoc($userdata);
    return $datarow['username'];
}

//오늘 날짜 기록 변수
$today = date("Y-m-d");

function delayfind(){
    //오늘 날짜 찍기
    $today = date("Y-m-d");
    //conn 데려오기
    $conn = $GLOBALS["conn"];
    //오늘날짜보다 과거이면서 반납일이 비어있으면 연체자입니다.
    $finder = mysqli_query($conn, "SELECT rent_mem FROM rental WHERE rent_due<'$today' AND rent_over IS NULL;");
    $finderlen = mysqli_num_rows($finder);
    for($z=0;$z<$finderlen;$z++){
        $delay = mysqli_fetch_assoc($finder);
        $no = $delay['rent_mem'];
        mysqli_query($conn, "UPDATE lib_mem SET delay=1 WHERE no=$no;");
    }
}

function cutprevdata(){
    $today = date("Y-m-d");
    $conn = $GLOBALS["conn"];
    $monthprev = date("Y-m-d",strtotime("$today -1 month"));
    $deldata = mysqli_query($conn, "SELECT no FROM rental WHERE rent_over < '$monthprev';");
    $dellen = mysqli_num_rows($deldata);
    for($x=0;$x<$dellen;$x++){
        $deleted = mysqli_fetch_assoc($deldata);
        mysqli_query($conn, "DELETE FROM rental WHERE no={$deleted['no']}");
    }
}

?>