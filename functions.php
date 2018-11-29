<?php

session_start();

$db = 'diplom';
$user = 'root';
$pass = '';
$host = 'localhost';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

$thetime = date('Y-m-d H:i:s', time());

function authorization($login, $password)
{
    global $pdo;
    $authorization = 'SELECT * FROM users WHERE user_name = :login AND user_pass = :password';
    $stmt = $pdo->prepare($authorization);
    $stmt->execute(["login" => "$login", "password" => "$password"]);
    $users = $stmt->fetchAll();
    foreach ($users as $user) {
        if (isset($user)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            return true;
        } else {
            return false;
        }
    }
}

function addUserNameAndQuestion($login)
{
    global $pdo;
    $checkExistedLogin = 'SELECT id FROM users WHERE user_name = ?';
    $stmt = $pdo->prepare($checkExistedLogin);
    $stmt->execute(["$login"]);
    addUser();
    addQuestion();

}
function addUser()
{
    global $pdo;
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $addUser = 'INSERT INTO users (user_name, user_email) VALUES (:login, :email)';
    $stmt = $pdo->prepare($addUser);
    $stmt->execute(["login" => "$user_name", "email" => "$user_email"]);
    $getSession = 'SELECT id FROM users WHERE user_name = ?';
    $stmt = $pdo->prepare($getSession);
    $stmt->execute(["$user_name"]);
    $_SESSION['user_name'] = $_POST['name'];
}

function addQuestion()
{

    global $pdo;
    $name = $_SESSION['user_name'];
    $question = $_POST['question'];
    $category = $_POST['category'];
    $addQuestion = 'INSERT INTO questions (category , name, question, is_enabled) VALUES (:category, :user_name, :question, :is_enabled)';
    $stmt = $pdo->prepare($addQuestion);
    $stmt->execute(["category" => "$category", "user_name" => "$name", "question" => "$question", "is_enabled" => "0"]);

}

function isAuthorized()
{
    if (!empty($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function getAdmins()
{

    global $pdo;
    $getAdmins = 'SELECT * FROM users WHERE  user_pass is not null';
    $stmt = $pdo->prepare($getAdmins);
    $stmt->execute();
    $tables = $stmt->fetchAll();
    return $tables;

}

function logout()
{
    session_destroy();
}

function deleteTask($user_id, $id)
{
    global $pdo;
    $deleteTask = 'DELETE FROM task WHERE user_id= :user_id AND id= :id LIMIT 1';
    $stmt = $pdo->prepare($deleteTask);
    $stmt->execute(["user_id" => $user_id, "id" => $id]);
}

function getTasks ($user_id)
{
    global $pdo;
    $getTable = 'SELECT id, description, assigned_user_id, date_added, is_done FROM task WHERE user_id= ? ORDER BY date_added ASC';
    $stmt = $pdo->prepare($getTable);
    $stmt->execute([$user_id]);
    $tables = $stmt->fetchAll();
    return $tables;

}

function deleteAdmin ($id)
{
    global $pdo;
    $deleteAdmin = 'DELETE FROM users WHERE users.id = ?';
    $stmt = $pdo->prepare($deleteAdmin);
    $stmt->execute([$id]);
}

function changePass($new_pass, $id)
{
    global $pdo;
    $changePass = 'UPDATE users SET user_pass = :new_pass WHERE users.id = :id';
    $stmt = $pdo->prepare($changePass);
    $stmt->execute(["new_pass" => "$new_pass", "id" => $id]);

}

function newAdmin($user_login, $pass, $email)
{

    global $pdo;
    $checkExistedLogin = 'SELECT user_name, user_pass FROM users WHERE user_name = ?';
    $stmt = $pdo->prepare($checkExistedLogin);
    $stmt->execute(["$user_login"]);
    $logins = $stmt->fetchAll();
    foreach ($logins as $login) {
        if (isset($login['user_name']) && isset($login['user_pass'])) {
            return false;
        }
    }
    foreach ($logins as $login) {
        if (!isset($login['user_pass'])) {
            $newAdmin = 'INSERT INTO users (user_name, user_pass, user_email) VALUES (:login, :pass, :email)';
            $stmt = $pdo->prepare($newAdmin);
            $stmt->execute(["login" => $user_login, "pass" => $pass, "email" => $email]);
            return true;
        }
    }
}

function getCategories()
{
    global $pdo;
    $getCategories = 'SELECT * FROM categories';
    $stmt = $pdo->prepare($getCategories);
    $stmt->execute();
    $categories = $stmt->fetchAll();
    return $categories;

}


function countQuestionsInCategory()
{
    global $pdo;
    $countQuestionsInCategory = 'SELECT category as "Категория",COUNT(*) AS "Всего вопросов", SUM(CASE is_enabled WHEN 1 THEN 1 ELSE 0 END) AS "Опубликованных ответов",
    SUM(CASE is_enabled WHEN 0 THEN 1 ELSE 0 END) AS "Не опубликованных" FROM questions GROUP BY question';
    $stmt = $pdo->prepare($countQuestionsInCategory);
    $stmt->execute();
    $categories = $stmt->fetchAll();
    return $categories;
}
function getDeligatedTasks($user_id)
{
    global $pdo;
    $getDeligatedTasks = 'SELECT user_id, description, assigned_user_id, login FROM task t INNER JOIN user u ON u.id=t.assigned_user_id WHERE t.assigned_user_id = :user_id AND :user_id not in (SELECT t.user_id FROM task)';
    $stmt = $pdo->prepare($getDeligatedTasks);
    $stmt->execute(["user_id" => $user_id]);
    $deligates = $stmt->fetchAll();
    return $deligates;
}

function countTask($user_id)
{
    global $pdo;
    $nRows = $pdo->query('SELECT count(*) FROM task WHERE user_id = "' . "$user_id" . '"OR assigned_user_id ="' . "$user_id" . '"')->fetchColumn();
    echo $nRows;
}