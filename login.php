<?php
session_start();

// Подключение к базе данных
require_once 'db_connect.php';

$errors = [];

// define('SMARTCAPTCHA_SERVER_KEY', '<ключ_сервера>');

// function check_captcha($token) {
//     $ch = curl_init();
//     $args = http_build_query([
//         "secret" => SMARTCAPTCHA_SERVER_KEY,
//         "token" => $token,
//         "ip" => $_SERVER['REMOTE_ADDR'],
//     ]);
//     curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 1);

//     $server_output = curl_exec($ch);
//     $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     if ($httpcode !== 200) {
//         echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
//         return true;
//     }
//     $resp = json_decode($server_output);
//     return $resp->status === "ok";
// }

// $token = $_POST['smart-token'];
// if (check_captcha($token)) {
//     echo "Passed\n";
// } else {
//     echo "Robot\n";
// }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = $_POST['password'];

    // Поиск пользователя по email или телефону
    $query = "SELECT * FROM users WHERE email='$login' OR phone='$login'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = "Неправильный пароль!";
        }
    } else {
        $errors[] = "Пользователь не найден!";
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
    <!-- <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script> -->
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
                                Авторизация
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
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <label for="login">Email или Телефон</label>
                                            <input type="text" class="form-control" id="login" name="login" placeholder="Введите email или телефон" required>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <div class="col-md-6 mx-auto">
                                            <label for="password" class="mb-2">Пароль <span style="color: #60B1B4;">*</span></label>
                                            <input id="password" type="password" class="form-control auth_inp" name="password" autocomplete="off" placeholder="Введите пароль">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <!-- <div
                                            id="captcha-container"
                                            class="smart-captcha"
                                            data-sitekey="<ключ_клиента>"
                                        ></div> -->
                                        <div class="col-md-6 mx-auto d-flex justify-content-center">
                                            <button type="submit" name="register" class="btn btn-block btn-dark">Авторизироваться</button>
                                        </div>
                                    </div>
                                </form>

                                <p class="text-center mt-3">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>

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
