<?php
include "common.php";
include "header.php";

if(!isset($_SESSION['log'])){
    warning("로그인이 필요합니다.","login.php");
}

$memdata = mysqli_query($conn, "SELECT * FROM lib_mem WHERE userid='{$_SESSION['id']}';");
$memrow  = mysqli_fetch_assoc($memdata);

$resi_num = $memrow['resi_num'];
$resi_num = explode("-",$resi_num);

$resi_num1 = $resi_num[0];
$resi_num2 = $resi_num[1];

$phone = $memrow['phone'];
$phone = explode("-",$phone);
$phone1 = $phone[0];
$phone2 = $phone[1];
$phone3 = $phone[2];



?>

<h2>
    회원정보 수정
</h2>

<div class="card border-primary">
    <div class="card-header">
        기본 정보
    </div>
    <form id="modify" action="mem_modify_insert.php" method="post">
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
            아이디 *
            <span id="chkresult" class="badge badge-danger"></span>
            <input id="userid" name="userid" type="text" placeholder="input your id." class="form-control" maxlength="20" readonly value="<?php echo "{$memrow['userid']}"; ?>"/>
        </label>
        <label>
            비밀번호 *
            <input id="userpw" name="userpw" type="password" class="form-control"/>
        </label>
        <label>
            비밀번호 확인 *
            <input id="cpw" name="cpw" type="password" class="form-control" />
        </label>
        <label>
            성명 *
            <input id="username" name="username" type="text" class="form-control" readonly value="<?php echo "{$memrow['username']}"; ?>" />
        </label>
        <label>
            주민등록번호 *
            <div class="row">
                <div class="col-6">
                    <input readonly id="resi_num1" type="text" name="resi_num1" class="form-control" maxlength="6" value="<?php echo "$resi_num1"; ?>" />
                </div>
                <div class="col-6">
                    <input readonly id="resi_num2" type="password" name="resi_num2" class="form-control" maxlength="7" value="<?php echo "$resi_num2"; ?>" />
                </div>
            </div>
        </label>
        <label>
            전화번호 *
            <div class="row">
                <div class="col-4">
                    <select id="phone1" name="phone1" class="form-control">
                        <option value="">선택</option>
                        <option value="010">010</option>
                        <option value="011">011</option>
                        <option value="016">016</option>
                    </select>  
                </div>
                <div class="col-4">
                    <input id="phone2" name="phone2" type="text" class="form-control" maxlength="4" value="<?php echo "$phone2"; ?>"/>
                </div>
                <div class="col-4">
                    <input id="phone3" name="phone3" type="text" class="form-control" maxlength="4" value="<?php echo "$phone3"; ?>" />
                </div>
            </div>
        </label>
        <label>
            주소 *
            <input id="address" name="address" type="text" class="form-control" value="<?php echo "{$memrow['address']}"; ?>" />
        </label>
        <div class="text-center mt-3 mb-3">
            <button type="button" id="submit1" class="btn btn-outline-primary">확인</button>
            <button type="reset" class="btn btn-outline-secondary">초기화</button>
        </div>
    </form>
</div>

<div class="text-right">
    <button class="backtrac btn btn-outline-info mt-2 mb-2" type="button">뒤로가기</button>
</div>

<script>
    $(document).ready(function(){
        $("#phone1>option[value=<?php echo $phone1; ?>]").attr("selected","true");
    
        $("#submit1").click(function(){
            if($("#userpw").val() == $("#cpw").val()){
                $("#modify").submit();
            }else{
                alert("비밀번호를 확인해주세요.");
            }
        });
        
        
    });
</script>


<?php
include "footer.php";
?>