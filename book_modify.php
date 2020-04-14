<?php
include "common.php";
include "header.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$bookno = $_GET['no'];
$bookdata = mysqli_query($conn, "SELECT * FROM bookinfo WHERE no=$bookno;");
$book = mysqli_fetch_assoc($bookdata);

$codedata = mysqli_query($conn, "SELECT * FROM lib_code WHERE publname IS NULL;");
$codelen = mysqli_num_rows($codedata);

$publdata = mysqli_query($conn, "SELECT * FROM lib_code WHERE classname IS NULL;");
$publlen = mysqli_num_rows($publdata);

?>

<h2 class="mt-2">책 정보 수정</h2>

<hr/>

<form id="bookmod" action="book_modify_insert.php" method="get">
    <style>
        form label {
            width: 100%;
            max-width: 700px;
            display: block;
            margin: auto;
            margin-top: 1em;
        }
    </style>
    <input name="no" value="<?php echo $bookno; ?>" hidden />
    <label>
        번호
        <input class="form-control mt-2 mb-3" value="<?php echo $book['no']; ?>" readonly />
    </label>
    <label>
        책 제목
        <input id="booktitle" name="booktitle" class="form-control mt-2 mb-3" type="text" maxlength="30" placeholder="제목을 입력해주세요." value="<?php echo $book['bookname']; ?>" />
    </label>
    <label>
        저자
        <input id="author" name="author" class="form-control mt-2 mb-3" type="text" maxlength="30" placeholder="저자를 입력해주세요." value="<?php echo $book['author']; ?>"/>
    </label>
    <label>
        <button type="button" class="btn btn-primary" data-toggle="popover" data-html="true" data-trigger="focus" title="출판사" data-content="<?php for($i=0;$i<$publlen;$i++){
    $publ = mysqli_fetch_assoc($publdata);
    echo $publ['publname'].' : '.$publ['code'].'<br/>';
} ?>" >출판사</button>
        <input id="publ" name="publ" class="form-control mt-2 mb-3" type="text" maxlength="6" placeholder="출판사 코드를 입력해주세요. 예)000235" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $book['publ']; ?>" />
    </label>
    <label>
        출판일
        <input id="origindate" name="origindate" type="date" class="form-control mt-2 mb-3" value="<?php echo $book['origindate']; ?>" />
    </label>
    <label>
        <button type="button" class="btn btn-primary" data-toggle="popover" data-html="true" data-trigger="focus" title="장르" data-content="<?php for($j=0;$j<$codelen;$j++){
    $code = mysqli_fetch_assoc($codedata);
    echo $code['classname'].' : '.$code['code'].'<br/>';
} ?>" >분류</button>
        <input id="bookclass" name="bookclass" class="form-control mt-2 mb-3" type="text" maxlength="3" placeholder="예)053" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $book['bookclass']; ?>" />
    </label>
    <label>
        ISBN
        <input id="isbn" name="isbn" class="form-control mt-2 mb-3" type="text" maxlength="17" placeholder="예)000-00-0000-000-0" value="<?php echo $book['isbn']; ?>" />
    </label>
    <label>
        서고위치
        <input id="loca" name="loca" class="form-control mt-2 mb-3" type="text" maxlength="3" placeholder="예)023" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $book['locate']; ?>" />
    </label>
    <label>
        이용제한
        <select id="avail" name="avail" class="form-control">
        <?php
        if($book['avail'] == 0){
            echo "<option value='0' selected>이용가능</option>";
            echo "<option value='1'>이용불가</option>";
        }elseif($book['avail'] == 1){
            echo "<option value='0'>이용가능</option>";
            echo "<option value='1' selected>이용불가</option>";
        }
        ?>
        </select>
    </label>
    <div class="text-center mt-3 mb-3">
        <button id="booksubmit" type="button" class="btn btn-primary">수정</button>
        <button type="reset" class="btn btn-outline-secondary">초기화</button>
    </div>

    
    <script>
        $("#booksubmit").click(function(){
            var a1 = $("#booktitle").val().length;
            var a2 = $("#author").val().length;
            var a3 = $("#publ").val().length;
            var a4 = $("#origindate").val().length;
            var a5 = $("#bookclass").val().length;
            var a6 = $("#isbn").val().length;
            var a7 = $("#loca").val().length;
            var result = a1*a2*a3*a4*a5*a6*a7;
            if(result == 0){
                alert("항목을 모두 채워주세요.");
            }else{
                $("#bookmod").submit();
            }
        });
    </script>
    
</form>

<div class="text-right">
    <button class="backtrac btn btn-outline-info mb-2" type="button">뒤로가기</button>
</div>









<?php
include "footer.php";
?>