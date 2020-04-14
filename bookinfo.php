<?php
include "common.php";
include "header.php";

if(!isset($_SESSION['log'])){
    warning("로그인이 필요한 페이지입니다.","index.php");
}

$order = $_GET['order'];

if(empty($order)){
    warning("잘못된 접근입니다.","index.php");
}

$orders = "";
if($order == "n"){
    $order = "no";
    $orders = "n";
}elseif($order == "a"){
    $order = "accum_count";
    $orders = "a";
}elseif($order == "d"){
    $order = "origindate";
    $orders = "d";
}

$by = $_GET['by'];
$fix = $_GET['fix'];

if($by == "a" && $fix == "off"){
    $by = "d";
    $pageby = "a";
    $orby = "ASC";
}elseif($by == "d" && $fix == "off"){
    $by = "a";
    $pageby = "d";
    $orby = "DESC";
}elseif($fix == "on" && $by == "a"){
    $pageby = "a";
    $by = "a";
    $orby = "ASC";
}elseif($fix == "on" && $by == "d"){
    $pageby = "d";
    $by = "d";
    $orby = "DESC";
}

if($orders == "n" && $fix == "on" && $by == "d"){
    $by = "a";
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

$totalbook = mysqli_query($conn, "SELECT no FROM bookinfo");
$totallen = mysqli_num_rows($totalbook);
$bookdata = mysqli_query($conn, "SELECT * FROM bookinfo ORDER BY $order $orby LIMIT $firstpostno, $postlen;");
$booklen = mysqli_num_rows($bookdata);

// 전체 페이지 수 세기
$pagelen = ceil($totallen / $postlen);

?>

<h2 class="mt-2 mb-3">도서 정보</h2>
<hr/>
<div class="text-right mb-2">
    <a href="bookinfo.php?order=n&fix=off&by=<?php echo $by; ?>" class="btn btn-outline-info">최신순</a>
    <a href="bookinfo.php?order=a&fix=off&by=<?php echo $by; ?>" class="btn btn-outline-info">누적대여순</a>
    <a href="bookinfo.php?order=d&fix=off&by=<?php echo $by; ?>" class="btn btn-outline-info">출판일순</a>
</div>
<form id="search" action="booksearch.php" method="get" class="input-group mb-2" style="max-width: 400px; float:right;">
    <div class="input-group-prepend">
        <select id="searchtype" class="form-control" name="searchtype" style="border-top-right-radius:0px; border-bottom-right-radius:0px;">
            <option value="0">제목</option>
            <option value="1">저자</option>
        </select>
    </div>
    <input id="searchtxt" class="form-control" type="text" name="searchtxt" placeholder="검색어를 입력해주세요."/>
    <div class="input-group-append">
        <button id="searchbtn" class="btn btn-outline-secondary" type="button">검색</button>
    </div>
    <input name="fix" type="text" value="<?php echo $fix; ?>" hidden />
    <input name="order" type="text" value="<?php echo $orders; ?>" hidden />
    <input name="by" type="text" value="<?php echo $by; ?>" hidden />
</form>
<table class="table">
    <tr class="bg-dark text-white">
        <th>번호</th>
        <th>제목</th>
        <th>저자</th>
        <th>출판일</th>
        <th>누적대여수</th>
        <th>서고위치</th>
        <th>대여중</th>
    </tr>
   <?php
    for($i=0;$i<$postlen;$i++){
        $book = mysqli_fetch_assoc($bookdata);
        if($book['no']!=""){
            echo "<tr>";
            echo "<td>{$book['no']}</td>";
            if($book['accum_count'] > 3){
                $best = "<span class='badge badge-success'>Best</span>";
            }else{
                $best = "";
            }
            echo "<td><a href='bookview.php?no={$book['no']}'>{$book['bookname']} $best</a></td>";
            echo "<td>{$book['author']}</td>";
            echo "<td>{$book['origindate']}</td>";
            echo "<td>{$book['accum_count']}</td>";
            echo "<td>{$book['locate']}</td>";
            if($book['taken'] == 0){
                $taken = "<span class='badge badge-info'>미대여</span>";
            }elseif($book['taken'] == 1){
                $taken = "<span class='badge badge-danger'>대여중</span>";
            }
            echo "<td>$taken</td>";
            echo "</tr>";
        }
    }
    ?> 
</table>
<?php
if($blockno <= 0){
    $prevdisabled = "disabled";
}else{
    $prevdisabled = "";
}

$prevurl = $firstbtnno - 1;
$prevurl = "bookinfo.php?order=$orders&by=$by&page=$prevurl";

if($blockno >= $blocklen - 1){
    $nextdisabled = "disabled";
}else{
    $nextdisabled = "";
}

$nexturl = $firstbtnno + $pbtnlen;
$nexturl = "bookinfo.php?order=$orders&by=$by&page=$nexturl";

?>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo $prevdisabled; ?>">
            <a class="page-link" href="<?php echo $prevurl; ?>" aria-label="Previous">
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
            if($bno <= $pagelen){
                echo "<li class='page-item $active'><a class='page-link' href='bookinfo.php?order=$orders&page=$pno&by=$pageby&fix=on'>$bno</a></li>";
            }
        }
        ?>
        <li class="page-item <?php echo $nextdisabled; ?>">
            <a class="page-link" href="<?php echo $nexturl; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<div class="text-right">
    <button class="backtrac btn btn-outline-info mb-2" type="button">뒤로가기</button>
</div>

<?php
include "footer.php";
?>