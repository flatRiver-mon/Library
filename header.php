<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Pyungkang Kim" />
        <meta name="viewport" content="initial-scale=1, width=device-width" />
        <title>도서관 정보 시스템</title>
        <link href="css/common.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </head>
    
    <body>
        <header class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="index.php">도서관</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item btn-group">
                            <a class="btn btn-primary" href="index.php">메인</a>
                        <?php
                            if($_SESSION['userlv'] < 1){
                                echo "<a class='white btn btn-primary' href='join.php'>회원가입</a>";
                                echo "<a class='white btn btn-primary' href='index.php'>로그인</a>";
                            }else{
                                echo "<a class='btn btn-success' href='bookinfo.php?order=n&fix=on&by=d'>도서정보</a>";
                                echo "<a class='btn btn-secondary' href='mem_modify.php'>회원정보 수정</a>";
                                echo "<a class='btn btn-danger' href='logout.php'>로그아웃(".id2name($_SESSION['id']).")</a>";
                            }
                            if($_SESSION['userlv'] >= 8){
                              echo "<a class='btn btn-warning' href='admin.php?type=rental'>관리자</a>";
                          }
                        ?>
                        </li>
                    </ul>
                </div>
            </nav>
            
        </header>
        
        <section class="container">
        
