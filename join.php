<?php
include "common.php";
include "header.php";

if($_SESSION['userlv'] > 0){
    warning("가입하신 회원은 사용하실 수 없습니다.",-1);
}
?>

<h2>
    회원가입
</h2>

<div class="card border-primary">
    <div class="card-header">
        기본 정보
    </div>
    <form id="join" action="join_insert.php" method="post">
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
            <input id="userid" name="userid" type="text" placeholder="input your id." class="form-control" maxlength="20"/>
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
            <input id="username" name="username" type="text" class="form-control" />
        </label>
        <label>
            주민등록번호 *
            <div class="row">
                <div class="col-6">
                    <input id="resi_num1" type="text" name="resi_num1" class="form-control" maxlength="6" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                </div>
                <div class="col-6">
                    <input id="resi_num2" type="password" name="resi_num2" class="form-control" maxlength="7" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
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
                    <input id="phone2" name="phone2" type="text" class="form-control" maxlength="4" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"/>
                </div>
                <div class="col-4">
                    <input id="phone3" name="phone3" type="text" class="form-control" maxlength="4" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                </div>
            </div>
        </label>
        <label>
            주소 *
            <input id="address" name="address" type="text" class="form-control" />
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
        var chk = false;
        
        $("#submit1").click(function(){
            var a1 = $("#userid").val().length;
            var a2 = $("#userpw").val().length;
            var a3 = $("#cpw").val().length;
            var a4 = $("#username").val().length;
            var a5 = $("#resi_num1").val().length;
            var a6 = $("#resi_num2").val().length;
            var a7 = $("#phone1").val().length;
            var a8 = $("#phone2").val().length;
            var a9 = $("#phone3").val().length;
            var a10 = $("#address").val().length;
            var result = a1*a2*a3*a4*a5*a6*a7*a8*a9*a10;
            if(result == 0 || result == NaN){
                alert("필수 입력칸을 채워주세요.");
            }else{
                if($("#userpw").val() == $("#cpw").val()){
                    if(chk){
                        $("#join").submit();
                    }else{
                        alert("아이디를 체크해 주세요.");
                    }
                }else{
                    alert("비밀번호를 확인해주세요.");
                }
            }
        });
        
        $("#userid").keyup(function(){
            var key = $(this).val();

            $.ajax({
                method: "get",
                url: "chkid.php",
                data: "userid="+key,
                dataType: "html",
                success: function(result){
                    if(result == 0){
                        $("#chkresult").text("사용할 수 있는 아이디");
                        $("#chkresult").removeClass("badge-danger");
                        $("#chkresult").removeClass("badge-info");
                        $("#chkresult").addClass("badge-success");
                        chk = true;
                    }else if(result == 1){
                        $("#chkresult").text("사용할 수 없는 아이디");
                        $("#chkresult").removeClass("badge-success");
                        $("#chkresult").removeClass("badge-info");
                        $("#chkresult").addClass("badge-danger");
                        chk = false;
                    }else if(result == "empty"){
                        $("#chkresult").text("아이디를 입력하세요.");
                        $("#chkresult").removeClass("badge-success");
                        $("#chkresult").addClass("badge-info");
                        chk = false;
                    }
                }
            });
        });
    });
</script>


<?php
include "footer.php";
?>