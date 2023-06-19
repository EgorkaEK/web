<?php

session_start();

require_once 'DB.php';

$db = new Photos\DB();

if (isset($_POST['photo_id'])) {
    $photo_id = $_POST['photo_id'];
    $photo = $db->get_photo_by_id($photo_id);

    $image = $_FILES['image']['name'];
    $text = $_POST['text'];
    $tags = $_POST['tags'];

    if (!empty($image)) {
        $uploadPath = 'images/';

        $fileName = uniqid() . '_' . time() . '.png';

        $destination = $uploadPath . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $db->update_photo($photo_id, $destination, $text, $tags);
            echo 'Изменения сохранены успешно!';
        } else {
            echo 'Ошибка загрузки файла.';
        }
    } else {
        $db->update_photo($photo_id, $photo['Image'], $text, $tags);
        echo 'Изменения сохранены успешно!';
    }
} else {
    echo 'Идентификатор фотографии не указан.';
}
