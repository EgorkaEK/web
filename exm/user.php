<?php
session_start();
$user_id = $_SESSION["user_id"] ?? false;
if ($user_id) {
    require "vendor/autoload.php";
    $db = new \Photos\DB();
    $data = $db->get_user_photos($user_id);
    $role_id = $db->get_user_role($user_id);
}
if (isset($_GET["error"])) {
    $error = "Неверный логин или пароль!";
}
if (isset($_GET["sign_error"])) {
    $sign_error = "Такой логин уже существует.";
}
if (isset($_GET["sign_error_empty"])) {
    $sign_error_empty = "Введите имя, почту и пароль.";
}
if (isset($_GET["sign_success"])) {
    $sign_success = "Вы успешно зарегистрировались!";
}
if($user_id == true){
	header('Location: http://localhost/exm/index.php');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	 <title>Личный кабинет</title>
	 <meta charset="UTF-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="style.css">
	 <link rel="stylesheet" href="media.css">
	 <link rel="stylesheet" href="m_tab.css">
	 <script src="script.js" defer></script>
</head>
<body>
<?php include "header.php" ?>
<?php if ($user_id): ?>
	 <h1>Галерея пользователя</h1>
	 <div id="grid">
         <?php foreach ($data as $photo): ?>
             <?= (new Photos\Photo($photo["ID"], $photo["Image"], $photo["Text"]))->get_html() ?>
         <?php endforeach; ?>
	 </div>
<?php else: ?>
	 <div class="form">
		  <form action="login.php" method="post">
			   <h2>Авторизация</h2>
			   <input type="text" placeholder="Логин" name="login">
			   <input type="password" placeholder="Пароль" name="password">
			   <button>Войти</button>
              <?php if (isset($_GET["error"])): ?>
				   <p class="error"><?= $error ?></p>
              <?php endif ?>
		  </form>
		  <form action="signup.php" method="post">
			   <h2>Регистрация</h2>
			   <input type="text" placeholder="Имя" name="name">
			   <input type="text" placeholder="Логин" name="login">
			   <input type="password" placeholder="Пароль" name="password">
			   <button>Зарегистрироваться</button>
              <?php if (isset($_GET['sign_error'])): ?>
				   <p class="error"> <?= $sign_error ?> </p>
              <?php endif ?>
              <?php if (isset($_GET['sign_error_empty'])): ?>
				   <p class="error"> <?= $sign_error_empty ?> </p>
              <?php endif ?>
              <?php if (isset($_GET['sign_success'])): ?>
				   <p class="success"> <?= $sign_success ?> </p>
              <?php endif ?>
		  </form>
	 </div>
<?php endif; ?>

<?php include "add_form.php"; ?>

<div id="popup_photo">
	 <img src="" alt="">
</div>
</body>
</html>