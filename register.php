<?php
session_start();

// Подключение к базе данных
require_once 'db_connect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка на совпадение паролей
    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают!";
    }

    // Проверка на уникальность почты и телефона
    $query = "SELECT * FROM users WHERE email='$email' OR phone='$phone'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Пользователь с такой почтой или телефоном уже существует!";
    }

    // Если нет ошибок, добавляем пользователя в базу данных
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO users (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$hashed_password')";

        if (mysqli_query($conn, $insert_query)) {
            // Получение данных нового пользователя
            $user_query = "SELECT * FROM users WHERE email='$email'";
            $user_result = mysqli_query($conn, $user_query);
            
            if (mysqli_num_rows($user_result) == 1) {
                $user = mysqli_fetch_assoc($user_result);
                
                // Сохранение пользователя в сессии
                $_SESSION['user'] = $user;
                
                // Перенаправление на страницу профиля
                header("Location: profile.php");
                exit();
            } else {
                $errors[] = "Ошибка при получении данных нового пользователя";
            }
        } else {
            $errors[] = "Ошибка при выполнении запроса: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница регистрации</title>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse navbar__source" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li>
                            <a href="index.php">Главная</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($user)) { ?>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="profile.php">
                                        <i class="mdi mdi-exit-to-app pr-1"></i> Личный кабинет
                                    </a>
                                    <a class="dropdown-item" href="logout.php">
                                        <i class="mdi mdi-exit-to-app pr-1"></i> Выйти
                                    </a>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a id="navbarDropdown" class="nav-link" href="login.php">
                                    Войти
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 py-lg-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card auth_form mx-auto my-auto p-4">
                            <div class="card-header h4 text-black text-uppercase text-center">
                                Регистрация
                            </div>

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <form method="POST" action="register.php" id="register_form">
                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <label for="name" class="mb-2">Имя <span style="color: #60B1B4;">*</span></label>
                                            <input id="name" class="form-control auth_inp" name="name" required="" autocomplete="name" autofocus="" placeholder="Введите имя">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <label for="phone" class="mb-2">Телефон <span style="color: #60B1B4;">*</span></label>
                                            <input id="phone" class="form-control auth_inp" name="phone" required="" autofocus="" placeholder="Введите телефон">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <label for="email" class="mb-2">Почта <span style="color: #60B1B4;">*</span></label>
                                            <input id="email" class="form-control auth_inp" name="email" required="" autofocus="" placeholder="Введите email">
                                        </div>
                                    </div>

                                    <div class="password_section">
                                        <div class="form-group row mb-2">
                                            <div class="col-md-6 mx-auto">
                                                <label for="password" class="mb-2">Пароль <span style="color: #60B1B4;">*</span></label>
                                                <input id="password" type="password" class="form-control auth_inp" name="password" autocomplete="off" placeholder="Введите пароль">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2">
                                            <div class="col-md-6 mx-auto">
                                                <label for="confirm_password" class="mb-2">Подтвержение пароля <span style="color: #60B1B4;">*</span></label>
                                                <input id="confirm_password" type="password" class="form-control auth_inp" name="confirm_password" autocomplete="off" placeholder="Подтвердите пароль"> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <h6><span style="color: #60B1B4;">*</span> - поля обязательные для заполнения</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto d-flex justify-content-center">
                                            <button type="submit" name="register" class="btn btn-block btn-dark">Зарегистрироваться</button>
                                        </div>
                                    </div>
                                </form>

                                <p class="text-center mt-3">Уже есть аккаунт? <a href="login.php">Войти</a></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer></footer>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>
