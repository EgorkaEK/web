<?php

use Photos\DB;

session_start();

require 'DB.php';

$db = new DB();

if (isset($_POST['recordId'])) {
    $recordId = $_POST['recordId'];
    $db->delete_record($recordId);
    echo 'Запись и все ее фотографии успешно удалены!';
} else {
    echo "Идентификатор записи не определен.";
}

