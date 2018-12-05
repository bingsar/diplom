<?php
require_once 'functions.php';
$errors = [];
$errorses = [];
$q = 1;
if (!isAuthorized()) {
    header('Location: index.php');
    die;
}

if (isset($_GET['id'])) {
    deleteAdmin($_GET['id']);
}

if (isset($_GET['category'])) {
    deleteCategory($_GET['category']);
}

if (isset($_GET['question'])) {
    deleteQuestion($_GET['question']);
}

if (isset($_GET['make_enabled_id'])) {
    questionIsEnabled($_GET['make_enabled_id']);
}

if (isset($_GET['make_hidden_id'])) {
    questionIsHidden($_GET['make_hidden_id']);
}

if (isset($_POST['new_pass'])) {
    changePass($_POST['new_pass'], $_POST['delete_id']);
}

if (isset($_POST['new_author'])) {
    changeName($_POST['new_author'], $_POST['id']);
}

if (isset($_POST['new_question_text'])) {
    changeQuestion($_POST['new_question_text'], $_POST['id']);
}

if (isset($_POST['edit_answer'])) {
    editAnswer($_POST['edit_answer'], $_POST['id']);
}

if (isset($_POST['question_change_category_id'])) {
    changeQuestionCategory($_POST['category_id'], $_POST['question_change_category_id']);
}

if (isset($_POST['admin_login'])) {
    if (checkAdmin($_POST['admin_login'], $_POST['admin_pass'])) {
        newAdmin($_POST['admin_login'], $_POST['admin_pass'], $_POST['admin_email']);
        header('Location: panel.php');
        die;
    } else {
            $errors[] = 'Такой логин уже существует!';
    }
}

if (isset($_POST['new_category'])) {
    if (checkCategory($_POST['new_category'])) {
        newCategory($_POST['new_category']);
        header('Location: panel.php');
    } else {
        $errorses[] = 'Такая категория уже создана!';
    }
}

$time=time();
$thetime = date('d.m.Y', $time);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Admin</title>
</head>
<body>


<section id="todolist">
    <div align="center" class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="form-wrap">

                    <h1><label for="lg">Список администраторов</label></h1>

                    <table class="table table-bordered table-inverse">
                        <thead>
                        <tr>

                            <th>id</th>
                            <th>Name</th>
                            <th>Password</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach (getAdmins() as $table) {
                            ?>
                            <tr>
                                <td><?php echo $table['id']?><a href="panel.php?id=<?php echo $table['id'];?>">  <span class="glyphicon glyphicon-remove" title="Удалить администратора"></span></a>
                                </td>
                                <td><?php echo $table['user_name']?></td>
                                <td><?php echo $table['user_pass']?><form method="POST"><input type="text" name="new_pass" required><input type="hidden" name="delete_id" value="<?php echo $table['id']; ?>"><input type="submit" value="Изменить пароль"></form></td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                    <hr>
                    <h3>Создать нового аминистратора</h3>
                    <form method="POST">
                        <input type="text" name="admin_login" placeholder="Введите логин..." required>
                        <input type="text" name="admin_pass" placeholder="Введите пароль..." required>
                        <input type="text" name="admin_email" placeholder="Введите email..." required>
                        <input type="submit" value="Создать">
                    </form>
                    <div>
                        <br>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li ><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="withoutAnswer.php">Список вопросов без ответа</a>
                    <hr>
                    <br>
                    <a class="btn btn-danger btn-lg" href="logout.php">Выход</a>
                    <br>
                </div>
            </div>
                <div class="col-md-8">
                <table class="table table-bordered table-inverse">
                    <h3>Список категорий</h3>
                    <thead>
                    <tr>

                        <th>Категория</th>
                        <th>Всего вопросов</th>
                        <th>Кол-во опубликованных</th>
                        <th>Сколько без ответа</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach (countQuestionsInCategory() as $category) { ?>
                    <tr>
                        <td><?php echo $category['Категория']?></td>
                        <td><?php echo $category['Всего вопросов']?></td>
                        <td><?php echo $category['Опубликованных ответов']?></td>
                        <td><?php echo $category['Не опубликованных']?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>

                    <hr>
                    <h3>Создать новую категорию</h3>
                    <form method="POST">
                        <input type="text" name="new_category" placeholder="Введите название..." required>
                        <input type="submit" value="Создать">
                    </form>

                    <div>
                        <br>
                        <ul>
                            <?php foreach ($errorses as $errorer): ?>
                                <li><?= $errorer; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <hr>
                    <h3>Вопросы по категориям</h3>
                    <br>
                    <?php foreach (getCategories() as $category) { $q++; ?>
                        <div class="panel-group" id="accordion">

                                <div class="panel panel-default">
                                    <div class="panel-heading">

                                        <h4 class="panel-title">

                                            <a data-toggle="collapse" data-parent="#accordion" href="#<?= $q; ?>">
                                                <?= $category['category'] ?><span style="padding-left: 100px"><a href="panel.php?category=<?= $category['category'];?>"><span class="glyphicon glyphicon-remove" title="Удалить категорию"></span></a></span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="<?= $q; ?>" class="panel-collapse collapse collapse in">

                                        <div class="panel-body">

                                            <table class="table table-bordered table-inverse">
                                                <thead>
                                                <tr>
                                                    <th>Имя автора</th>
                                                    <th>Вопрос</th>
                                                    <th>Ответ</th>
                                                    <th>Дата создания</th>
                                                    <th>Статус</th>
                                                    <th>Поменять тему</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach (getQuestionsInfo($category['category']) as $question_info) { ?>
                                                    <tr>
                                                        <td><?php echo $question_info['name']?>

                                                            <form method="POST">
                                                                <input type="text" name="new_author" placeholder="Введите имя..." required>
                                                                <input type="hidden" name="id" value="<?= $question_info['question'] ?>">
                                                                <input type="submit" value="Изменить имя">
                                                            </form>
                                                        </td>
                                                        <td><?php echo $question_info['question']?><a href="panel.php?question=<?php echo $question_info['question'];?>"><span class="glyphicon glyphicon-remove" title="Удалить вопрос"></span></a>

                                                            <form method="POST">
                                                                <input type="text" name="new_question_text" placeholder="Введите вопрос..." required>
                                                                <input type="hidden" name="id" value="<?= $question_info['question'] ?>">
                                                                <input type="submit" value="Редактировать">
                                                            </form>

                                                        </td>
                                                        <td><?php echo $question_info['answer'] ?>

                                                                <form method="POST">
                                                                    <input type="text" name="edit_answer" placeholder="Введите ответ..." required>
                                                                    <input type="hidden" name="id" value="<?= $question_info['question'] ?>">
                                                                    <?php if (isset($question_info['answer'])) {?><input type="submit" value="Изменить"><?php } else { ?> <input type="submit" value="Добавить ответ"> <?php } ?>
                                                                </form>

                                                        </td>
                                                        <td><?php echo $question_info['Time']?></td>
                                                        <td><?php if (($question_info['is_hidden'] == 1)) {echo 'Скрыто'; ?><a href="panel.php?make_enabled_id=<?= $question_info['question'] ?>"> Опубликовать </a> <?php } elseif ($question_info['is_enabled'] == 0) {echo 'Ожидает ответа';} else {echo 'Опубликован'; ?> <a href="panel.php?make_hidden_id=<?= $question_info['question'] ?>"> Скрыть </a> <?php } ?></td>
                                                        <td>
                                                            <form method="POST">
                                                                <input name="question_change_category_id" type="hidden" value="<?php echo $question_info['question']?>">
                                                                <select name="category_id">
                                                                    <?php foreach (getCategories() as $newCategory){ ?>
                                                                    <option selected value="<?= $newCategory['category'] ?>">
                                                                        <?= $newCategory['category'] ?>
                                                                        <?php } ?>
                                                                    </option>
                                                                </select>
                                                                <button type="submit">Поменять</button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                <?php } ?>
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>