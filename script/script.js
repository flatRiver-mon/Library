$(document).ready(function(){
            
    //회원 상태에 따라서 제한
    var chkrentmem = false;
    var chkrecallmem = false;
    
    //책의 상태에 따라서 제한
    var rentbook = false;
    var recallbook = false;
    
    // 로그인
    $("#logsubmit").click(function(){
        var logid = $("#logid").val().length;
        var logpw = $("#logpw").val().length;
        if(logid * logpw == 0){
            alert("아이디와 비밀번호를 확인해 주세요.");
        }else {
            $("#logform").submit();
        }
    });
    $("#logpw").keydown(function(e){
        var key = e.keyCode;
        if(key == 13){
            $("#logsubmit").trigger("click");
        }
    });
    
    // 회원 등급 설정
    $(".level").change(function(){
        if(confirm("정말로 수정하시겠습니까?")){
            var memno = $(this).parent().parent().children().eq(0).text();
            var memlv = $(this).val();
            $(".memno").attr("value",memno);
            $("#memlv").attr("value",memlv);
            $("#levelmod").submit();
        }else{
            var origin = $(this).attr("data");
            $(this).children("option").removeAttr("selected");
            $(this).children("option").eq(origin-1).attr("selected","true");
        }
    });
    
    // 회원 연체자 설정
    $(".delayed").change(function(){
        if(confirm("정말로 수정하시겠습니까?")){
            var memno = $(this).parent().parent().children().eq(0).text();
            var memdelay = $(this).val();
            $(".memno").attr("value",memno);
            $("#memdelay").attr("value",memdelay);
            $("#delaymod").submit();
        }else{
            var origin = $(this).val();
            $(this).children("option").removeAttr("selected");
            $(this).children("option").eq(origin-1).attr("selected","true");
        }
    });
    
    // 책 정보 등록
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
            $("#bookin").submit();
        }
    });
    
    // 책 정보 수정
    $(".book_mod").click(function(){
        if(confirm("책 정보를 수정하시겠습니까?")){
            var no = $(this).children().eq(0).text();
            location.href = "book_modify.php?no="+no;
        }
    });
    
    $(".book_del").click(function(){
        if(confirm("정말로 삭제하시겠습니까?")){
            var no = $(this).children().eq(0).text();
            location.href = "book_delete.php?no="+no;
        }
    });
    
    // 책 검색
    $("#searchbtn").click(function(){
        var slen = $("#searchtxt").val().length;
        if(slen == 0){
            alert("검색어를 입력하세요.");
        }else if(slen < 2){
            alert("검색어는 두글자 이상입니다.");
        }else{
            $("#search").submit();
        }
    });
    
    $("#searchtxt").keydown(function(e){
        var code = e.keyCode;
        if(code == 13){
            $("#searchbtn").trigger("click");
        }
    });
    
    //도서 대여
    $("#rentalsub").click(function(){
        var memlen = $("#rent_mem").val().length;
        var booklen = $("#rent_book").val().length;
        if(memlen * booklen == 0){
            alert("항목을 모두 채워주세요.");
        }else{
            if(chkrentmem && chkrentbook){
                $("#rental").submit();
            }else if(chkrentmem == false){
                var msg = $("#chkmem").text();
                alert(msg);
            }else if(chkrentbook == false){
                var msg = $("#chkbook").text();
                alert(msg);
            }
        }
    });
    
    //회원의 현재상황
    $("#rent_mem").keyup(function(){
        var key = $(this).val();

        $.ajax({
            method: "get",
            url: "chkmem.php",
            data: "userno="+key,
            dataType: "html",
            success: function(result){
                if(result < 5 && result >= 0){
                    $("#chkmem").text("현재 대여수 " + result);
                    $("#chkmem").removeClass("badge-danger");
                    $("#chkmem").addClass("badge-success");
                    chkrentmem = true;
                }else if(result >= 5){
                    $("#chkmem").text("대여가능 수를 초과했습니다.");
                    $("#chkmem").removeClass("badge-success");
                    $("#chkmem").addClass("badge-danger");
                    chkrentmem = false;
                }else if(result == -1){
                    $("#chkmem").text("해당 회원이 존재하지 않습니다.");
                    $("#chkmem").removeClass("badge-success");
                    $("#chkmem").addClass("badge-danger");
                    chkrentmem = false;
                }
            }
        });
    });
    
    //도서의 현재상황
    $("#rent_book").keyup(function(){
        var key = $(this).val();
        
        $.ajax({
            method: "get",
            url: "chkbook.php",
            data: "bookno="+key,
            dataType: "html",
            success:function(result){
                if(result == 0){
                    $("#chkbook").text("대여가능");
                    $("#chkbook").removeClass("badge-danger");
                    $("#chkbook").addClass("badge-success");
                    chkrentbook = true;
                }else if(result == 1){
                    $("#chkbook").text("대여불가능");
                    $("#chkbook").removeClass("badge-success");
                    $("#chkbook").addClass("badge-danger");
                    chkrentbook = false;
                }else if(result == 2){
                    $("#chkbook").text("이용제한중");
                    $("#chkbook").removeClass("badge-success");
                    $("#chkbook").addClass("badge-danger");         
                    chkrentbook = false;
                }else if(result == 3){
                    $("#chkbook").text("해당 도서가 존재하지 않습니다.");
                    $("#chkbook").removeClass("badge-success");
                    $("#chkbook").addClass("badge-danger");
                    chkrentbook = false;
                }
            }
        });
    });
    
    $("#rent_book").keydown(function(e){
        var code = e.keyCode;
        if(code == 13){
            $("#rentalsub").trigger("click");
        }
    });
    
    // 도서 반납
    $("#recallsub").click(function(){
        var memlen = $("#recall_mem").val().length;
        var booklen = $("#recall_book").val().length;
        if(memlen * booklen == 0){
            alert("항목을 모두 채워주세요.");
        }else{
            if(recallbook && recallmem){
                $("#recall").submit();
            }else if(recallmem == false){
                var msg = $("#chkrecallmem").text();
                alert(msg);
            }else if(recallbook == false){
                var msg = $("#chkrecallbook").text();
                alert(msg);
            }
        }
    });
    
    
    
    $("#recall_mem").keyup(function(){
        var key = $(this).val();
        
        $.ajax({
            method:"get",
            url: "chkmem.php",
            data: "userno="+key,
            dataType: "html",
            success:function(result){
                if(result <= 5 && result >= 0){
                    $("#chkrecallmem").removeClass("badge-danger");
                    $("#chkrecallmem").addClass("badge-success");
                    $("#chkrecallmem").text("현재 "+result+"권 대여중");
                    recallmem = true;
                }else if(result < 0){
                    $("#chkrecallmem").removeClass("badge-success");
                    $("#chkrecallmem").addClass("badge-danger");
                    $("#chkrecallmem").text("해당 회원이 존재하지 않습니다.");
                    recallmem = false;
                }
            }
        });
    });
    
    $("#recall_book").keyup(function(){
        var key = $(this).val();
        
        $.ajax({
            method:"get",
            url: "chkbook.php",
            data: "bookno="+key,
            dataType: "html",
            success:function(result){
                if(result == 1){
                    $("#chkrecallbook").text("대여중");
                    $("#chkrecallbook").removeClass("badge-danger");
                    $("#chkrecallbook").addClass("badge-success");
                    recallbook = true;
                }else if(result == 0){
                    $("#chkrecallbook").text("미대여");
                    $("#chkrecallbook").removeClass("badge-success");
                    $("#chkrecallbook").addClass("badge-danger");
                    recallbook = false;
                }else if(result == 3){
                    $("#chkrecallbook").text("해당 도서가 존재하지 않습니다.");
                    $("#chkrecallbook").removeClass("badge-success");
                    $("#chkrecallbook").addClass("badge-danger");
                    rentbook = false;
                }
            }
        });
    });
    
    $("#recall_book").keydown(function(e){
        var code = e.keyCode;
        if(code == 13){
            $("#recallsub").trigger("click");
        }
    });
    
    // 책 수정 삭제 tr에 마우스를 올려놓을때 색변경
    $(".book_mod").mouseover(function(){
        $(this).addClass("bg-info");
        $(this).addClass("text-white");
    });
    
    $(".book_mod").mouseout(function(){
        $(this).removeClass("bg-info");
        $(this).removeClass("text-white");
    });
    
    $(".book_del").mouseover(function(){
        $(this).addClass("bg-warning");
    });
    
    $(".book_del").mouseout(function(){
        $(this).removeClass("bg-warning");
    });
    
    //뒤로가기
    $(".backtrac").click(function(){
        history.go(-1);
    });
    
    //팝오버 스크립트
    $(function (){
        $('[data-toggle="popover"]').popover();
    });
    
    $('.popover-dismiss').popover({
        trigger: 'focus'
    });
    
    
    
});