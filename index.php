<?php
require_once 'functions.php';
$errors = [];
if (!empty($_POST['login']) && !empty($_POST['password'])) {
    if (authorization($_POST['login'], $_POST['password'])) {
        header('Location: panel.php');
        die;
    } else {
        $errors[] = 'Неверный логин или пароль';
    }
}

if (isset($_POST['name'])) {
    addUserNameAndQuestion($_POST['name']);
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
    <link rel="stylesheet" href="css/bootstrap.css">
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
            <div>
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
        <div class="col-md-6">
        <h1>Привет <em><?php if (isset($_SESSION['user_name'])) { echo $_SESSION['user_name']; } else { echo 'username*'; } ?></em></h1>
        <p>Добро пожаловать на сервис вопросов и ответов</p>
        <p>Здесь вы можете задать любой интересующий вас вопрос</p>
            <br>
            <p><strong>ИЛИ</strong> посмотрите ответы по категориям</p>
            <a href="#question" class="btn btn-primary btn-sm" role="button">Перейти &raquo;</a>
        </div>
        <div class="form-wrap col-md-6 alert-success">
        <h1>Задайте вопрос</h1>

        <form method="POST" role="form">
            <div class="form-group">
                <br>
                <div class="input-group">
                    <span class="input-group-addon">Ваше имя: </span>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon">Ваш email: </span>
                    <input type="text" name="email" class="form-control" required>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon">Ваш вопрос: </span>
                    <input type="text" name="question" class="form-control" required>
                </div>
                <br>

                <div class="input-group">
                    <span>Выберите категорию: </span><select class="form-control" id="inputGroupSelect04" name="category">
                        <?php foreach (getCategories() as $category) { ?>
                            <option value="<?= $category['category'] ?>"><?= $category['category'] ?></option>
                        <?php } ?>
                        </select>
                </div>

            </div>
            <button type="submit" class="btn btn-success">Cпросить</button>

        </form>
            <br>
        </div>

    </div>

</div>

<div class="container">
    <a name="question"></a>
    <div class="row">
        <div class="col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php foreach (getCategories() as $category) { ?>
                <li><a href=""><?= $category['category'] ?></a></li>
                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 main">
            <?php foreach (getCategories() as $category) { ?>
            <h1><?= $category['category'] ?></h1>
            <div class="panel-group" id="accordion">
                <?php foreach (getQuestions($category['category']) as $question) { $q++; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#<?= $q; ?>">
                                <?= $question['question']; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="<?= $q; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?= $question['answer']; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>