<?php
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>

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
                <div class="row justify-content-center text-center"> 
                        <h2>Главная страница</h2>
                </div>
            </div>
        </main>
        <footer></footer>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="/js/jquery.maskedinput.min.js"></script>
        <script src="/js/script.js"></script>
</body>
</html>