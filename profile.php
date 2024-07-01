<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} else {
    $user = $_SESSION['user'];
}

// print_r($user); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница профиля</title>

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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle navbar-name" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                Личный кабинет пользователя
                            </div>

                            <div class="card-body">

                                <div id="alertContainer" class="fixed-top px-3 py-2" style="display: none;">
                                    <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                                        Данные пользователя успешно обновлены.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                                
                                <p>Имя пользователя: <span id="userName"><?php echo $user['name']; ?></span></p>
                                <hr>
                                <p>Email пользователя: <span id="userEmail"><?php echo $user['email']; ?></span></p>
                                <hr>
                                <p>Телефон пользователя: <span id="userPhone"><?php echo $user['phone']; ?></span></p>
                                <hr>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                                    Изменить данные
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer></footer>
    </div>

    <!-- Модальное окно для изменения данных пользователя -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Изменение данных пользователя</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Форма для изменения данных пользователя -->
                    <form id="editForm" method="post" action="edit.php">
                        <input type="hidden" name="id" value="<?php echo ($user['id']); ?>">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="editName" name="editName" value="<?php echo ($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="editEmail" value="<?php echo ($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="editPhone" name="editPhone" value="<?php echo ($user['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" placeholder="Введите новый пароль" id="editPassword" name="editPassword">
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>
