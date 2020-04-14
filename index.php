<?php
include "common.php";
include "header.php";
?>

<h2>도서관 정보 시스템</h2>

<div class="alert border-primary mt-3 mb-3" style="max-width:600px; width:100%; margin:auto; padding: 3em;">
    <form id="logform" action="login_insert.php" method="post">
        <div class="row mb-2">
            <div class="col-3">아이디</div>
            <div class="col-9">
                <input id="logid" name="logid" type="text" class="form-control" />
            </div>
        </div>
        <div class="row">
            <div class="col-3">비밀번호</div>
            <div class="col-9">
                <input id="logpw" name="logpw" type="password" class="form-control" />
            </div>
        </div>
        <div class="text-center mt-3">
            <button id="logsubmit" type="button" class="btn btn-primary">Login</button>
        </div>
    </form>    
</div>

<?php
include "footer.php";
?>