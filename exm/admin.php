<?php
session_start();

require 'DB.php';
require 'Photo.php';

$db = new Photos\DB();

$user_id = $_SESSION['user_id'];
$role_id = $db->get_user_role($user_id);

if ($role_id != 1) {
    header("Location: index.php");
}

$data = $db->get_all_photos();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	 <meta charset="UTF-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <title>Панель администратора</title>
	 <link rel="stylesheet" href="style.css">
	 <link rel="stylesheet" href="media.css">
	 <link rel="stylesheet" href="m_tab.css">
	 <script src="script.js" defer></script>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<?php include "header.php"; ?>
<h1>Панель администратора</h1>

<div class="form">
	 <h1>Добавление фотографии</h1>
	 <form id="uploadForm" method="POST" enctype="multipart/form-data" onsubmit="return uploadForm();"
		   autocomplete="off">
		  <label for="image">Фотография:</label>
		  <input type="file" name="image" id="image" required><br>

		  <label for="text">Описание:</label>
		  <textarea name="text" id="text" required></textarea><br>

		  <label for="tags">Тэги:</label>
		  <input type="text" name="tags" id="tags" required><br>

		  <button type="submit">Добавить</button>
	 </form>
</div>


<div class="form">
	 <h1>Выберите запись для изменения:</h1>

	 <form method="POST" action="" autocomplete="off">
		  <label>
			   <select name="photo_id">
					<option selected disabled>Выберите запись</option>
                   <?php foreach ($data as $photo): ?>
						<option value="<?= $photo['ID'] ?>"><?= $photo['Text'] ?></option>
                   <?php endforeach; ?>
			   </select>
		  </label>
		  <button type="submit">Выбрать</button>
	 </form>

    <?php
    if (isset($_POST['photo_id'])) {
        $photo_id = $_POST['photo_id'];
        $photo = $db->get_photo_by_id($photo_id);
        ?>
		 <h1>Изменение записи:</h1>

		 <form id="editForm" method="POST" enctype="multipart/form-data" onsubmit="return editForm();"
			   autocomplete="off">
			  <input type="hidden" name="photo_id" value="<?= $photo['ID'] ?>">

			  <label for="image">Фотография:</label>
			  <input type="file" name="image" id="image"><br>

			  <label for="text">Описание:</label>
			  <textarea name="text" id="text"><?= $photo['Text'] ?></textarea><br>

			  <label for="tags">Тэги:</label>
			  <input type="text" name="tags" id="tags" value="<?= $photo['Tags'] ?>"><br>

			  <button type="submit">Сохранить</button>
		 </form>

		 <form id="deletePhoto" method="POST" autocomplete="off">
			  <input type="hidden" name="photo_id" value="<?= $photo['ID'] ?>">
			  <button type="button" onclick="deletePhoto()">Удалить фото</button>
		 </form>
        <?php
    }
    ?>

	 <h1>Удаление записей</h1>
	 <div class="form">
		  <form id="deleteForm" method="POST" onsubmit="return deleteForm();" autocomplete="off">
			   <h1>Доступные записи:</h1>
			   <label>
					<select name="recordId">
						 <option selected disabled>Выберите запись</option>
						<?php
                        $records = $db->get_all_photos();

                        foreach ($records as $record) {
                            $recordId = $record['ID'];
                            $recordName = $record['Text'];
                            echo "<option value='$recordId'>$recordName</option>";
                        }
                        ?>
					</select>
			   </label>
			   <button type="submit">Удалить запись</button>
		  </form>
	 </div>

</div>
</body>
<script>
    function uploadForm() {
        var formData = new FormData($('#uploadForm')[0]);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert(response);
            }
        });

        return false;
    }

    function editForm() {
        var formPhotoData = new FormData($('#editForm')[0]);

        $.ajax({
            url: 'edit.php',
            type: 'POST',
            data: formPhotoData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert(response);
                location.reload();
            }
        });

        return false;
    }

    function deletePhoto() {
        var confirmDelete = confirm("Вы уверены, что хотите удалить фотографию?");

        if (confirmDelete) {
            var photoId = $('input[name="photo_id"]').val();

            $.ajax({
                url: 'delete_photo.php',
                type: 'POST',
                data: {photo_id: photoId},
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    }

    function deleteForm() {
        var confirmDelete = confirm("Вы уверены, что хотите удалить эту запись и все её фото?");

        if (confirmDelete) {
            var recordId = $('[name="recordId"]').val();

            $.ajax({
                url: 'delete_record.php',
                type: 'POST',
                data: {recordId: recordId},
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }

        return false;
    }

</script>
</html>
