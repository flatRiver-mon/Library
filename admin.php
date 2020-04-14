<?php
include "common.php";
include "header.php";

if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","index.php");
}

$type = $_GET['type'];

if(empty($type)){
    warning("잘못된 접근입니다.","index.php");
}

$page = $_GET['page'];
if($page == ""){
    $page = 0;
}

// 전체 블록 수 세기
$blocklen = ceil($pagelen / $pbtnlen);

// 한 페이지에서 보여줄 첫번째 게시물 번호
$firstpostno = $page * $postlen;

// 현재 보고 있는 페이지가 몇번째 블록에 있는가?
$blockno = floor($page / $pbtnlen);

// 현재 보고있는 블록의 첫번째 버튼 번호는?
$firstbtnno = $blockno * $pbtnlen;

if($type == "member"){
    $data = mysqli_query($conn, "SELECT * FROM lib_mem ORDER BY no DESC LIMIT $firstpostno, $postlen;");
    $len = mysqli_num_rows($data);
    $mempagelen = ceil($len / $postlen);
    $memmode = "block";
    $bookmode = "none";
    $rentalmode = "none";
    $recallmode = "none";
    $bookcur = "none";
}else if($type == "book"){
    $bookmode = "block";
    $memmode = "none";
    $rentalmode = "none";
    $recallmode = "none";
    $bookcur = "none";
}else if($type == "rental"){
    $bookmode = "none";
    $memmode = "none";
    $rentalmode = "block";
    $recallmode = "none";
    $bookcur = "block";
}else if($type == "recall"){
    $bookmode = "none";
    $memmode = "none";
    $rentalmode = "none";
    $recallmode = "block";
    $bookcur = "block";
}

$codedata = mysqli_query($conn, "SELECT * FROM lib_code WHERE publname IS NULL;");
$codelen = mysqli_num_rows($codedata);

$publdata = mysqli_query($conn, "SELECT * FROM lib_code WHERE classname IS NULL;");
$publlen = mysqli_num_rows($publdata);

?>
<style>
    #bookrental {
        display: <?php echo "$rentalmode"; ?>;
    }
    #bookrecall {
        display: <?php echo "$recallmode"; ?>;
    }
    
    #member {
        display: <?php echo "$memmode"; ?>;
    }
    #book {
        display: <?php echo "$bookmode"; ?>;
    }
    #bookcur {
        display: <?php echo "$bookcur"?>;
    }
    
    .book_mod {
        cursor: pointer;
    }
    .book_del {
        cursor: pointer;
    }
</style>


<h2>관리자 페이지</h2>
<hr/>
<div class="row mt-3 mb-3">
    <div class="col-3">
        <a class="btn btn-warning btn-block" href="admin.php?type=rental">도서대여 관리</a>
    </div>
    <div class="col-3">
        <a class="btn btn-primary btn-block" href="admin.php?type=recall">도서반납 관리</a>
    </div>
    <div class="col-3">
        <a class="btn btn-success btn-block" href="admin.php?type=book">도서정보 관리</a>
    </div>
    <div class="col-3">
        <a class="btn btn-secondary btn-block" href="admin.php?type=member">회원정보 관리</a>
    </div>
</div>
<div id="bookrental">
    <h3>도서 대여 시스템</h3>
    <div class="card border-primary mt-3 mb-3">
        <form id="rental" name="rental" action="rental_insert.php" method="post">
            <label>
                회원 NO
                <span id="chkmem" class="badge badge-success"></span>
                <input id="rent_mem" placeholder="회원의 NO를 적어주세요." name="rent_mem" class="form-control" type="text" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
            </label>
            <label>
                대여 도서 NO
                <span id="chkbook" class="badge badge-success"></span>
                <input id="rent_book" placeholder="대여 도서의 NO를 적어주세요." name="rent_book" class="form-control" type="text" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
            </label>
            <div class="text-center mt-3 mb-3">
                <button id="rentalsub" type="button" class="btn btn-primary">대여등록</button>
                <button type="reset" class="btn btn-outline-secondary">초기화</button>
            </div>
        </form>
    </div>
    
    
</div>

<div id="bookrecall">
    <h3>도서 반납 시스템</h3>
    <div class="card border-primary mt-3 mb-3">
        <form id="recall" name="recall" action="recall_insert.php" method="get">
            <label>
                회원 NO
                <span id="chkrecallmem" class="badge badge-success"></span>
                <input id="recall_mem" placeholder="회원의 NO를 적어주세요." name="recall_mem" class="form-control" type="text" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
            </label>
            <label>
                반납 도서 NO
                <span id="chkrecallbook" class="badge badge-success"></span>
                <input id="recall_book" placeholder="대여 도서의 NO를 적어주세요." name="recall_book" class="form-control" type="text" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
            </label>
            <div class="text-center mt-3 mb-3">
                <button id="recallsub" type="button" class="btn btn-primary">도서반납</button>
                <button type="reset" class="btn btn-outline-secondary">초기화</button>
            </div>
        </form>
    </div>
</div>

<div id="bookcur">
    <h4>도서 대여 현황</h4>
    <table class="table">
        <tr class="bg-dark text-white">
            <th>대여순번</th>
            <th>대여도서</th>
            <th>대여회원</th>
            <th>대여날짜</th>
            <th>반납기한</th>
            <th class="text-center">대여현황</th>
        </tr>
        <?php
        $rentaldata = mysqli_query($conn, "SELECT * FROM rental WHERE rent_over IS NULL ORDER BY no DESC");
        $rentallen = mysqli_num_rows($rentaldata);
        for($m=0;$m<$rentallen;$m++){
            $rental = mysqli_fetch_assoc($rentaldata);
            $rent_date = $rental['rent_date'];
            $rent_due = date("Y-m-d",strtotime("$rent_date +1 week"));
            $takemem = mysqli_query($conn, "SELECT no,username FROM lib_mem WHERE no={$rental['rent_mem']}");
            $takemem = mysqli_fetch_assoc($takemem);
            $takenbook = mysqli_query($conn, "SELECT no,bookname FROM bookinfo WHERE no={$rental['rent_book']}");
            $takenbook = mysqli_fetch_assoc($takenbook);
            echo "<tr>";
            echo "<td>{$rental['no']}</td>";
            echo "<td>{$takenbook['bookname']} <span class='badge badge-secondary'>{$takenbook['no']}</span></td>";
            echo "<td>{$takemem['username']} <span class='badge badge-secondary'>{$takemem['no']}</span></td>";
            echo "<td>{$rental['rent_date']}</td>";
            echo "<td>{$rental['rent_due']}</td> ";
            if($rental['rent_over'] != NULL){
                $cur = "<span class='badge badge-success'>반납완료</span>";
            }elseif($rental['rent_over'] == NULL && $rental['rent_due'] < $today){
                $cur = "<span class='badge badge-danger'>연체된 도서</span>";
            }elseif($rental['rent_over'] == NULL && $rental['rent_due'] >= $today){
                $cur = "<span class='badge badge-info'>대여중</span>";
            }
            echo "<td class='text-center'>$cur</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<div id="book">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-bookinsert-tab" data-toggle="pill" href="#pills-bookinsert" role="tab" aria-controls="pills-bookinsert" aria-selected="true">도서정보 입력</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-bookmod-tab" data-toggle="pill" href="#pills-bookmod" role="tab" aria-controls="pills-bookmod" aria-selected="false">도서정보 수정</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-bookdel-tab" data-toggle="pill" href="#pills-bookdel" role="tab" aria-controls="pills-bookdel" aria-selected="false">도서정보 삭제</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-bookinsert" role="tabpanel" aria-labelledby="pills-home-bookinsert">
            <form id="bookin" action="book_insert.php" method="get">
                <style>
                    form label {
                        width: 100%;
                        max-width: 700px;
                        display: block;
                        margin: auto;
                        margin-top: 1em;
                    }
                </style>
                <label>
                    책 제목
                    <input id="booktitle" name="booktitle" class="form-control mt-2 mb-3" type="text" maxlength="30" placeholder="제목을 입력해주세요." />
                </label>
                <label>
                    저자
                    <input id="author" name="author" class="form-control mt-2 mb-3" type="text" maxlength="30" placeholder="저자를 입력해주세요." />
                </label>
                <label>
                    <button type="button" class="btn btn-primary" data-toggle="popover" data-html="true" data-trigger="focus" title="출판사" data-content="<?php for($n=0;$n<$publlen;$n++){
                    $publ = mysqli_fetch_assoc($publdata);
                    echo $publ['publname'].' : '.$publ['code'].'<br/>';
                    } ?>" >출판사</button>
                    <input id="publ" name="publ" class="form-control mt-2 mb-3" type="text" maxlength="4" placeholder="출판사 코드를 입력해주세요. 예)1000 대우출판사" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                </label>
                <label>
                    출판일
                    <input id="origindate" name="origindate" type="date" class="form-control mt-2 mb-3" />
                </label>
                <label>
                    <button type="button" class="btn btn-primary" data-toggle="popover" data-html="true" data-trigger="focus" title="장르" data-content="<?php for($o=0;$o<$codelen;$o++){
                    $code = mysqli_fetch_assoc($codedata);
                    echo $code['classname'].' : '.$code['code'].'<br/>';
                    } ?>" >분류</button>
                    <input id="bookclass" name="bookclass" class="form-control mt-2 mb-3" type="text" maxlength="3" placeholder="예)110 장편소설" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                </label>
                <label>
                    ISBN
                    <input id="isbn" name="isbn" class="form-control mt-2 mb-3" type="text" maxlength="17" placeholder="예)000-00-0000-000-0" />
                </label>
                <label>
                    서고위치
                    <input id="loca" name="loca" class="form-control mt-2 mb-3" type="text" maxlength="3" placeholder="예)101" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                </label>
                <div class="text-center mb-3">
                    <button id="booksubmit" type="button" class="btn btn-primary">등록</button>
                    <button type="reset" class="btn btn-outline-secondary">초기화</button>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="pills-bookmod" role="tabpanel" aria-labelledby="pills-bookmod-tab">
            <table class="table">
                <tr class="bg-dark text-white">
                    <th>번호</th>
                    <th>제목</th>
                    <th>저자</th>
                    <th>출판사</th>
                    <th>출판일</th>
                    <th>분류</th>
                    <th>ISBN</th>
                    <th>소장일</th>
                    <th>누적대여</th>
                    <th>서고위치</th>
                    <th>이용제한</th>
                    <th>대여상태</th>
                </tr>
                <?php
                $bookdata = mysqli_query($conn, "SELECT * FROM bookinfo ORDER BY no DESC;");
                $booklen = mysqli_num_rows($bookdata);
                // 전체 페이지 수 세기
                $pagelen = ceil($booklen / $postlen);
                for($k=0;$k<$booklen;$k++){
                    //if($book['no']!=""){
                    $bookinfo = mysqli_fetch_assoc($bookdata);
                    if($bookinfo['no']!=""){
                        $publdata = mysqli_query($conn, "SELECT publname FROM lib_code WHERE code={$bookinfo['publ']}");
                        $publ0 = mysqli_fetch_assoc($publdata);
                        $classdata = mysqli_query($conn, "SELECT classname FROM lib_code WHERE code={$bookinfo['bookclass']}");
                        $class0 = mysqli_fetch_assoc($classdata);
                        echo "<tr class='book_mod'>";
                        echo "<td>{$bookinfo['no']}</td>";
                        echo "<td>{$bookinfo['bookname']}</td>";
                        echo "<td>{$bookinfo['author']}</td>";
                        echo "<td>{$publ0['publname']}</td>";
                        echo "<td>{$bookinfo['origindate']}</td>";
                        echo "<td>{$class0['classname']}</td>";
                        echo "<td>{$bookinfo['isbn']}</td>";
                        echo "<td>{$bookinfo['get_date']}</td>";
                        echo "<td>{$bookinfo['accum_count']}</td>";
                        echo "<td>{$bookinfo['locate']}</td>";
                        if($bookinfo['avail'] == 0){
                            $avail = "이용가능";
                        }elseif($bookinfo['avail'] == 1){
                            $avail = "이용불가";
                        }
                        echo "<td>$avail</td>";
                        if($bookinfo['taken'] == 0){
                            $taken = "미대여";
                        }elseif($bookinfo['taken'] == 1){
                            $taken = "대여중";
                        }
                        echo "<td>$taken</td>";
                        echo "</tr>";
                    }
                }

                ?>
            </table>
        </div>
        <div class="tab-pane fade" id="pills-bookdel" role="tabpanel" aria-labelledby="pills-bookdel-tab">
            <table class="table">
                <tr class="bg-dark text-white">
                    <th>번호</th>
                    <th>제목</th>
                    <th>저자</th>
                    <th>출판사</th>
                    <th>출판일</th>
                    <th>분류</th>
                    <th>ISBN</th>
                    <th>소장일</th>
                    <th>누적대여</th>
                    <th>서고위치</th>
                    <th>이용제한</th>
                    <th>대여상태</th>
                </tr>
                <?php
                $booksdata = mysqli_query($conn, "SELECT * FROM bookinfo ORDER BY no DESC;");
                $bookslen = mysqli_num_rows($booksdata);
                // 전체 페이지 수 세기
                $pagelen = ceil($bookslen / $postlen);
                for($l=0;$l<$bookslen;$l++){
                    $booksinfo = mysqli_fetch_assoc($booksdata);
                    if($booksinfo['no']!=""){
                        $publdata = mysqli_query($conn, "SELECT publname FROM lib_code WHERE code={$booksinfo['publ']}");
                        $publ1 = mysqli_fetch_assoc($publdata);
                        $classdata = mysqli_query($conn, "SELECT classname FROM lib_code WHERE code={$booksinfo['bookclass']}");
                        $class1 = mysqli_fetch_assoc($classdata);
                        echo "<tr class='book_del'>";
                        echo "<td>{$booksinfo['no']}</td>";
                        echo "<td>{$booksinfo['bookname']}</td>";
                        echo "<td>{$booksinfo['author']}</td>";
                        echo "<td>{$publ1['publname']}</td>";
                        echo "<td>{$booksinfo['origindate']}</td>";
                        echo "<td>{$class1['classname']}</td>";
                        echo "<td>{$booksinfo['isbn']}</td>";
                        echo "<td>{$booksinfo['get_date']}</td>";
                        echo "<td>{$booksinfo['accum_count']}</td>";
                        echo "<td>{$booksinfo['locate']}</td>";
                        if($booksinfo['avail'] == 0){
                            $avail = "이용가능";
                        }elseif($booksinfo['avail'] == 1){
                            $avail = "이용불가";
                        }
                        echo "<td>$avail</td>";
                        if($booksinfo['taken'] == 0){
                            $taken = "미대여";
                        }elseif($booksinfo['taken'] == 1){
                            $taken = "대여중";
                        }
                        echo "<td>$taken</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>


<div id="member">
    <h3>회원정보 관리 시스템</h3>
    <table class="table">
        <tr>
            <th>번호</th>
            <th>아이디</th>
            <th>성명</th>
            <th>전화번호</th>
            <th>주소</th>
            <th>현재대여수</th>
            <th>누적대여수</th>
            <th>연체자여부</th>
            <th>가입일</th>
            <th>회원등급</th>
        </tr>
        <?php
            for($i=0;$i<$postlen;$i++){
                $row = mysqli_fetch_assoc($data);
                if($row['no']!=""){
                    echo "<tr>";
                    echo "<td>{$row['no']}</td>";
                    echo "<td>{$row['userid']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['phone']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>{$row['rental_count']}</td>";
                    echo "<td>{$row['accum_count']}</td>";
                    echo "<td>";
                    echo "<select class='delayed'>";
                    if($row['delay'] == 1){
                        $fine = "";
                        $delay = "selected";
                    }else{
                        $fine = "selected";
                        $delay = "";
                    }
                    echo "<option value='0'>정상</option>";
                    echo "<option value='1' $delay>연체</option>";
                    echo "</select>";
                    echo "</td>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>";
                    echo "<select class='level' data='{$row['userlv']}'>";
                    for($j=0;$j<9;$j++){
                        $num = $j + 1;
                        if($row['userlv'] == $num){
                            echo "<option selected disabled value='$num'>$num</option>";
                        }else{
                            echo "<option value='$num'>$num</option>";
                        }
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
    <?php
    if($blockno <= 0){
        $memprevdisabled = "disabled";
    }else{
        $memprevdisabled = "";
    }

    $memprevurl = $firstbtnno - 1;
    $memprevurl = "admin.php?type=member&page=$memprevurl";

    if($blockno >= $blocklen - 1){
        $memnextdisabled = "disabled";
    }else{
        $memnextdisabled = "";
    }

    $memnexturl = $firstbtnno + $pbtnlen;
    $memnexturl = "admin.php?type=member&page=$memnexturl";

    ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $memprevdisabled; ?>">
                <a class="page-link" href="<?php echo $memprevurl; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            for($j=0;$j<$pbtnlen;$j++){
                $pno = $firstbtnno + $j;
                $bno = $pno + 1;
                if($pno == $page){
                    $active = "active";
                }else{
                    $active = "";
                }
                if($bno <= $mempagelen){
                    echo "<li class='page-item $active'><a class='page-link' href='admin.php?type=member&page=$pno'>$bno</a></li>";
                }
            }
            ?>
            <li class="page-item <?php echo $memnextdisabled; ?>">
                <a class="page-link" href="<?php echo $memnexturl; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<form id="levelmod" action="memmod_insert.php" method="get">
    <input type="text" name="mode" value="level" hidden />
    <input type="text" class="memno" name="memno" hidden />
    <input type="text" id="memlv" name="memlv" hidden />
</form>
<form id="delaymod" action="memmod_insert.php" method="get">
    <input type="text" name="mode" value="delay" hidden />
    <input type="text" class="memno" name="memno" hidden />
    <input type="text" id="memdelay" name="memdelay" hidden />
</form>

<div class="text-right">
    <button class="backtrac btn btn-outline-info mt-2 mb-2" type="button">뒤로가기</button>
</div>

<?php
include "footer.php";
?>