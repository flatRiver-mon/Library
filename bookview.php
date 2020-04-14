<?php
include "common.php";
include "header.php";

$no = $_GET['no'];

if($no == ""){
    warning("잘못된 접근입니다.","index.php");
}

$bookdata = mysqli_query($conn, "SELECT * FROM bookinfo WHERE no=$no;");
$book = mysqli_fetch_assoc($bookdata);

$publdata = mysqli_query($conn, "SELECT publname FROM lib_code WHERE code={$book['publ']}");
$publ = mysqli_fetch_assoc($publdata);

$classdata = mysqli_query($conn, "SELECT classname FROM lib_code WHERE code={$book['bookclass']}");
$classes = mysqli_fetch_assoc($classdata);

$rentdata = mysqli_query($conn, "SELECT * FROM rental WHERE rent_book=$no AND rent_over IS NULL;");
$rent = mysqli_fetch_assoc($rentdata);

if($book['avail'] == 0){
    $avail = "이용가능";
}elseif($book['avail'] == 1){
    $avail = "이용불가";
}
if($book['taken'] == 0){
    $taken = "미대여";
}elseif($book['taken'] == 1){
    $taken = "대여중";
}

?>



<div class="card mt-2 mb-2 border-primary">
    <div class="card-header">도서 정보</div>
    <div class="card-body">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon1">제목</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['bookname']; ?>" readonly aria-describedby="basic-addon1">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon2">저자</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['author']; ?>" readonly aria-describedby="basic-addon2">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon3">출판사</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $publ['publname']; ?>" readonly aria-describedby="basic-addon3">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon4">출판일</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['origindate']; ?>" readonly aria-describedby="basic-addon4">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon5">분류</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $classes['classname']; ?>" readonly aria-describedby="basic-addon5">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon6">ISBN</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['isbn']; ?>" readonly aria-describedby="basic-addon6">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon7">소장일</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['get_date']; ?>" readonly aria-describedby="basic-addon7">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon8">누적대여수</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['accum_count']; ?>" readonly aria-describedby="basic-addon8">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon9">서고위치</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $book['locate']; ?>" readonly aria-describedby="basic-addon9">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white" id="basic-addon10">이용제한</span>
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $avail; ?>" readonly aria-describedby="basic-addon10">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <?php
                    if($book['taken'] == 1){
                        echo "<button type='button' class='btn btn-danger' data-trigger='focus' data-toggle='popover' data-html='true' title='대여기간' data-content='대여일 : {$rent['rent_date']} <br/> 반납예정일 : {$rent['rent_due']}'>대여중</button>";
                    }else{
                        $week = date("Y-m-d",strtotime("$today +7 days"));
                        echo "<button type='button' class='btn btn-success' data-trigger='focus' data-toggle='popover' data-html='true' title='대여기간' data-content='대여일 : $today <br/> 반납예정일 : $week'>대여가능</button>";
                    }
                ?>
<!--                <button type='button' class='btn btn-primary' data-trigger='focus' data-toggle='popover' title='' data-content=''></button>-->
            </div>
            <input type="text" class="form-control bg-light" value="<?php echo $taken; ?>" readonly aria-describedby="basic-addon11">
        </div>
    </div>
</div>

<div class="text-right">
    <button class="backtrac btn btn-outline-info mt-2 mb-2" type="button">뒤로가기</button>
</div>

<?php
include "footer.php";
?>