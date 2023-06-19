<?php

use Photos\DB;

session_start();

require 'DB.php';

$db = new DB();

$uid = $_SESSION['user_id'];
$image = $_FILES['image']['name'];
$text = $_POST['text'];
$tags = $_POST['tags'];

$uploadPath = 'images/';

$fileName = uniqid() . '_' . time() . '.png';

$destination = $uploadPath . $fileName;

if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
    $db->new_photo($uid, $destination, $text, $tags);
    echo 'Фотография успешно добавлена!';
} else {
    echo 'Ошибка загрузки файла.';
}

