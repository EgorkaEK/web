<?php

require_once 'DB.php';

$db = new Photos\DB();

if (isset($user_id)) {
    $role_id = $db->get_user_role($user_id);
}
?>


<header>
	 <div class="popup">
		  <a href="index.php">Главная страница</a>
		  <?php if (!$user_id): ?>
			<a href="user.php">Личный кабинет</a>
		  <?php endif; ?>
         <?php if ($role_id == 1): ?>
			  <a href="admin.php">Админ панель</a>
         <?php endif; ?>
         <?php if ($user_id): ?>
			  <a href="logout.php">Выход</a>
         <?php endif; ?>
	 </div>
	 <div class="mobile_icon">
		  <img src="free-icon-menu-747327.png" alt="">
	 </div>
</header>
