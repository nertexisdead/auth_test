<?php
session_start();

// Удаление всех данных сессии
session_unset();

// Уничтожение сессионной переменной
session_destroy();

// Перенаправление на страницу входа
header("Location: index.php");
exit();
?>
