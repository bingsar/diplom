<?php
require_once 'functions.php';
$errors = [];
$errorses = [];
if (!isAuthorized()) {
    header('Location: index.php');
    die;
}

if (isset($_GET['id'])) {
    deleteAdmin($_GET['id']);
}

if (isset($_POST['new_pass'])) {
    changePass($_POST['new_pass'], $_POST['delete_id']);
}

if (isset($_POST['admin_login'])){
    if (newAdmin($_POST['admin_login'], $_POST['admin_pass'], $_POST['admin_email'])) {
        header('Location: panel.php');
        die;
    } else {
            $errors[] = 'Такой логин уже существует!';
    }
}

if (isset($_POST['newCategory'])){
    if (newCategory($_POST['newCategory'])) {
        header('Location: panel.php');
        die;
    } else {
        $errorses[] = 'Такая категория уже создана!';
    }
}

$time=time();
$thetime = date('d.m.Y', $time);

if (isset($_POST['id']))  {
    deleteTask($_SESSION['user_id'], $_POST['id']);
}

if (isset($_POST['task_id']))  {
    updateAssignedUser($_POST['assigned_user_id'], $_POST['task_id'], $_SESSION['user_id']);
}

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
                                <td><?php echo $table['id']?><a href="panel.php?id=<?php echo $table['id'];?>"><span class="glyphicon glyphicon-remove" title="Удалить администратора"></span></a>
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
                        <input type="text" name="newCategory" placeholder="Введите название..." required>
                        <input type="submit" value="Создать">
                    </form>
                    <div>
                        <br>
                        <ul>
                            <?php foreach ($errorses as $errorer): ?>
                                <li ><?= $errorer; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>


                    <h3>Делегированные дела для пользователя - <?php echo $_SESSION['user_login']; ?></h3>
                    <table class="table table-bordered table-inverse">
                        <thead>
                        <tr>

                            <th>id Создателя</th>
                            <th>Описание задания</th>
                            <th>id Исполнителя</th>
                            <th>Имя исполнителя</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach (getDeligatedTasks($_SESSION['user_id']) as $deligated) {
                            ?>
                            <tr>
                                <td><?php echo $deligated['user_id']?></td>
                                <td><?php echo $deligated['description']?></td>
                                <td><?php echo $deligated['assigned_user_id']?></td>
                                <td><?php echo $deligated['login']?></td>
                            </tr>
                        <?php } var_dump($_POST);

                        var_dump($_SESSION);?>
                        </tbody>
                    </table>
                    <h3>Количество дел: <?php countTask($_SESSION['user_id']); ?></h3>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>