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
    global $thetime;
    $name = $_SESSION['user_name'];
    $question = $_POST['question'];
    $category = $_POST['category'];
    $addQuestion = 'INSERT INTO questions (category , name, question, is_enabled, Time) VALUES (:category, :user_name, :question, :is_enabled, :time)';
    $stmt = $pdo->prepare($addQuestion);
    $stmt->execute(["category" => "$category", "user_name" => "$name", "question" => "$question", "is_enabled" => "0", "time" => $thetime]);

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

function deleteAdmin ($id)
{
    global $pdo;
    $deleteAdmin = 'DELETE FROM users WHERE users.id = ?';
    $stmt = $pdo->prepare($deleteAdmin);
    $stmt->execute([$id]);
}

function deleteQuestion($question)
{

    global $pdo;
    $deleteQuestion = 'DELETE FROM questions WHERE question = ?';
    $stmt = $pdo->prepare($deleteQuestion);
    $stmt->execute([$question]);

}

function changePass($new_pass, $id)
{
    global $pdo;
    $changePass = 'UPDATE users SET user_pass = :new_pass WHERE users.id = :id';
    $stmt = $pdo->prepare($changePass);
    $stmt->execute(["new_pass" => "$new_pass", "id" => $id]);

}

function checkAdmin($login, $pass)
{
    global $pdo;
    $checkExistedLogin = 'SELECT user_name, user_pass FROM users WHERE user_name = ?';
    $stmt = $pdo->prepare($checkExistedLogin);
    $stmt->execute(["$login"]);
    $logins = $stmt->fetchAll();

    if (empty($logins)) {
        return true;
    } else {
        return false;
    }
}

function newAdmin($user_login, $pass, $email)
{
    global $pdo;
    $newCategory = 'INSERT INTO users (user_name, user_pass, user_email) VALUES (:login, :pass, :email)';
    $stmt = $pdo->prepare($newCategory);
    $stmt->execute(["login" => $user_login, "pass" => $pass, "email" => $email]);
    return true;
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
    $countQuestionsInCategory = 'SELECT category as "Категория", COUNT(*) AS "Всего вопросов", SUM(CASE is_enabled WHEN 1 THEN 1 ELSE 0 END) AS "Опубликованных ответов",
    SUM(CASE is_enabled WHEN 0 THEN 1 ELSE 0 END) AS "Не опубликованных" FROM questions GROUP BY category';
    $stmt = $pdo->prepare($countQuestionsInCategory);
    $stmt->execute();
    $categories = $stmt->fetchAll();
    return $categories;
}
function getQuestions($category)
{
    global $pdo;
    $getQuestions = 'SELECT question, answer FROM questions WHERE is_enabled = 1 AND category = ?';
    $stmt = $pdo->prepare($getQuestions);
    $stmt->execute(["$category"]);
    $questions = $stmt->fetchAll();
    return $questions;
}

function getQuestionsInfo($category)
{
    global $pdo;
    $getQuestionsInfo = 'SELECT category, name, question, answer, Time, is_enabled, is_hidden FROM questions WHERE category = ?';
    $stmt = $pdo->prepare($getQuestionsInfo);
    $stmt->execute(["$category"]);
    $questions = $stmt->fetchAll();
    return $questions;
}

function checkCategory($category)
{
    global $pdo;
    $checkCategory = 'SELECT category FROM categories WHERE category = ?';
    $stmt = $pdo->prepare($checkCategory);
    $stmt->execute(["$category"]);
    $categorieses = $stmt->fetchAll();

    if (empty($categorieses)) {
        return true;
    } else {
        return false;
    }
}

function newCategory($category)
{
    global $pdo;
    $newCategory = 'INSERT INTO categories(category) VALUES (:category)';
    $stmt = $pdo->prepare($newCategory);
    $stmt->execute(["category" => $category]);
    return true;
}

function deleteCategory($category)
{
    global $pdo;
    $deleteCategory = 'DELETE FROM categories WHERE category = ?';
    $stmt = $pdo->prepare($deleteCategory);
    $stmt->execute(["$category"]);
}

function changeName($name, $id)
{
    global $pdo;
    $changeName = 'UPDATE questions SET name = :author WHERE question = :id';
    $stmt = $pdo->prepare($changeName);
    $stmt->execute(["author" => $name, "id" => $id]);
}

function changeQuestion($newQuestion, $id)
{
    global $pdo;
    $changeQuestion = 'UPDATE questions SET question = :question WHERE question = :id';
    $stmt = $pdo->prepare($changeQuestion);
    $stmt->execute(["question" => $newQuestion, "id" => $id]);
}

function questionIsEnabled($id)
{
    global $pdo;
    $questionIsEnabled = 'UPDATE questions SET is_enabled= 1, is_hidden = 0 WHERE question = ?';
    $stmt = $pdo->prepare($questionIsEnabled);
    $stmt->execute(["$id"]);
}

function questionIsHidden($id)
{
    global $pdo;
    $questionIsHidden = 'UPDATE questions SET is_enabled= 0, is_hidden = 1 WHERE question = ?';
    $stmt = $pdo->prepare($questionIsHidden);
    $stmt->execute(["$id"]);
}

function editAnswer($answer, $id)
{
    global $pdo;
    $editAnswer = 'UPDATE questions SET answer = :answer, is_enabled= 0, is_hidden = 1  WHERE question = :id';
    $stmt = $pdo->prepare($editAnswer);
    $stmt->execute(["answer" => $answer, "id" => $id]);
}

function changeQuestionCategory($newCategory, $id)
{
    global $pdo;
    $changeQuestionCategory = 'UPDATE questions SET category = :newCategory WHERE question = :id';
    $stmt = $pdo->prepare($changeQuestionCategory);
    $stmt->execute(["newCategory" => $newCategory, "id" => $id]);
}

function getQuestionWithoutAnswer()
{
    global $pdo;
    $getQuestionOrderBy = 'SELECT category, question, Time FROM questions  WHERE answer IS NULL ORDER BY Time DESC';
    $stmt = $pdo->prepare($getQuestionOrderBy);
    $stmt->execute();
    $orderedBy = $stmt->fetchAll();
    return $orderedBy;
}