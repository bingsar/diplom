<?php
require_once 'functions.php';
$errors = [];
if (!empty($_POST['login']) && !empty($_POST['password'])) {
    if (authorization($_POST['login'], $_POST['password'])) {
        header('Location: todo.php');
        die;
    } else {
        $errors[] = 'Неверный логин или пароль';
    }
}
if (!empty($_POST['newlogin'])) {
    if (checkExistedLogin($_POST['newlogin'])){
        header('Location: todo.php');
        die;
    } else {
        $errorsLogin[] = 'Такой логин уже существует';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PHP - Diplom</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="css/bootstrap-theme.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="js/bootstrap.js"></script>




</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Дипломная работа по курсу «PHP/SQL: back-end разработка и базы данных»</a>



        </div>
        <div class="navbar-collapse collapse">

            <form method="POST" class="navbar-form navbar-right" role="form">
                <div class="form-group">
                    <input type="text" placeholder="Логин" name="login" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Пароль" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Войти</button>
            </form>
            <div style="color: white;">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li ><?= $error ?></li>
            <?php endforeach; ?>
            </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron">
    <div class="container">
        <h1>Привет username*</h1>
        <p>Добро пожаловать на сервис вопросов и ответов</p>
        <p>Здесь вы можете задать любой интересующий вас вопрос</p>
        <div class="form-wrap">
        <form method="POST" role="form">
            <div class="form-group">
                <input type="text" placeholder="Задайте вопрос" name="question" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Cпросить</button>
        </form>
        </div>
        <div class="form-wrap">
            <br>
            <p>Или посмотрите ответы по категориям</p>
            <a href="#question" class="btn btn-primary btn-sm" role="button">Перейти &raquo;</a>
        </div>
    </div>
</div>

<div class="container">
    <a name="question"></a>
    <div class="row">
        <div class="col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">PHP</a></li>
                <li><a href="#">Python</a></li>
                <li><a href="#">Angular</a></li>
                <li><a href="#">JavaScript</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">C++</a></li>
                <li><a href="">C#</a></li>
                <li><a href="">Ruby</a></li>
                <li><a href="">Swift</a></li>
            </ul>
        </div>
        <div class="col-md-10 main">
            <h1>PHP</h1>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Ошибка - file_get_contents when url doesn't exist
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">


                            You need to check the HTTP response code:<br>
                            <br>
                            function get_http_response_code($url) {<br>
                            $headers = get_headers($url);<br>
                            return substr($headers[0], 9, 3);<br>
                            }<br>
                            if(get_http_response_code('http://somenotrealurl.com/notrealpage') != "200"){<br>
                            echo "error";<br>
                            }else{<br>
                            file_get_contents('http://somenotrealurl.com/notrealpage');<br>
                            }<br>

                            <br>

                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                Пункт Группы Свертывания #2
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                Пункт Группы Свертывания #3
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
            </div>

            </div>

        </div>
    </div>

</div>



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>

</body>
</html>