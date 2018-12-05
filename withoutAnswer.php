<?php
require_once 'functions.php';

if (!isAuthorized()) {
    header('Location: index.php');
    die;
}

if (isset($_GET['deleteQuestion'])) {
    deleteQuestion($_GET['deleteQuestion']);
}

if (isset($_POST['new_question_text'])) {
    changeQuestion($_POST['new_question_text'], $_POST['id']);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Без ответа</title>
</head>
<body>

<div class="container">
    <a name="question"></a>
    <div class="row">


        <div class="col-md-12 main">
            <table class="table table-bordered table-inverse">
                <h3>Список категорий</h3> <span><a href="panel.php">В панель администратора</a></span>
                <thead>
                <tr>

                    <th>Категория</th>
                    <th>Вопрос</th>
                    <th>Дата создания</th>

                </tr>
                </thead>
                <tbody>
                <?php
                foreach (getQuestionWithoutAnswer() as $order) { ?>
                    <tr>
                        <td><?php echo $order['category']?></td>
                        <td><?php echo $order['question']?><a href="withoutAnswer.php?deleteQuestion=<?php echo $order['question'];?>"><span class="glyphicon glyphicon-remove" title="Удалить Вопрос"></span></a>

                            <form method="POST">
                                <input type="text" name="new_question_text" placeholder="Введите вопрос..." required>
                                <input type="hidden" name="id" value="<?= $order['question'] ?>">
                                <input type="submit" value="Редактировать">
                            </form>

                        </td>
                        <td><?php echo $order['Time']?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>