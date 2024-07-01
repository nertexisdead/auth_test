<?php
session_start();

// Подключение к базе данных
require_once 'db_connect.php';

$userId = $_POST['id'];
$editName = $_POST['editName'];
$editEmail = $_POST['editEmail'];
$editPhone = $_POST['editPhone'];

if (!empty($_POST['editPassword'])) {
    $password = $_POST['editPassword'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET name='$editName', email='$editEmail', phone='$editPhone', password='$hashed_password' WHERE id=$userId";
} else {
    $sql = "UPDATE users SET name='$editName', email='$editEmail', phone='$editPhone' WHERE id=$userId";
}

if ($conn->query($sql) === TRUE) {
    // Успешное обновление данных
    $sql_get_user = "SELECT * FROM users WHERE id=$userId";
    $result = $conn->query($sql_get_user);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Обновляем данные в сессии
        $_SESSION['user'] = $user;
        
        echo json_encode(array("status" => "success", "user" => $user));
    } else {
        echo json_encode(array("status" => "error", "message" => "Ошибка при получении данных пользователя"));
    }
} else {
    // Ошибка при обновлении данных
    echo json_encode(array("status" => "error", "message" => "Ошибка при обновлении данных: " . $conn->error));
}

// Закрытие соединения с базой данных
$conn->close();
?>
