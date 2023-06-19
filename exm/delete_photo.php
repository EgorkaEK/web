<?php

session_start();

require_once 'DB.php';

$db = new Photos\DB();

if (isset($_POST['photo_id'])) {
    $photo_id = $_POST['photo_id'];
    $db->delete_photo($photo_id);
    echo 'Фотография успешно удалена!';
} else {
    echo "Идентификатор фотографии не определен.";
}