<?php

namespace Photos;

use mysqli;

class DB
{
    static $host = "localhost";
    static $user = "root";
    static $password = "";
    static $database = "photos";
    public $link;

    public function __construct()
    {
        $this->link = new mysqli(DB::$host, DB::$user, DB::$password, DB::$database);
        $this->link->set_charset("utf8");
    }

    public function get_all_photos()
    {
        $sql_result = $this->link->query("SELECT * FROM `photos` ORDER BY `ID` DESC");
        if ($sql_result->num_rows) {
            return $sql_result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function get_user_role($user_id)
    {
        $user_id = $this->link->real_escape_string($user_id);
        $sql_result = $this->link->query("SELECT `role_id` FROM `users` WHERE `ID` = '$user_id'");
        if ($sql_result->num_rows) {
            $user = $sql_result->fetch_assoc();
            return $user["role_id"];
        }
        return false;
    }


    public function get_user_photos($uid)
    {
        $uid = $this->link->real_escape_string($uid);
        $sql_result = $this->link->query("SELECT * FROM `photos` WHERE `Uid` = $uid ORDER BY `ID` DESC");
        if ($sql_result->num_rows) {
            return $sql_result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function check_user($login, $password)
    {
        $login = $this->link->real_escape_string($login);
        $password = $this->link->real_escape_string($password);
        $sql_result = $this->link->query("SELECT * FROM `users` WHERE `Email` = '$login' AND `Password` = '$password'");
        if ($sql_result->num_rows) {
            $user = $sql_result->fetch_assoc();
            return $user["ID"];
        }
        return false;
    }

    public function check_login($login)
    {
        $login = $this->link->real_escape_string($login);
        $sql_result = $this->link->query("SELECT * FROM `users` WHERE `Email` = '$login'");
        if ($sql_result->num_rows) {
            return true;
        }
        return false;
    }

    public function new_user($name, $login, $password)
    {
        $name = $this->link->real_escape_string($name);
        $login = $this->link->real_escape_string($login);
        $password = $this->link->real_escape_string($password);
        if (empty($name) && empty($login) && empty($password)) {
            return false;
        } else {
            $this->link->query("INSERT INTO `users` (Name, Password, Email) VALUES ('$name', '$password','$login')");
        }
        return [];
    }

    public function new_photo($uid, $image, $text, $tags)
    {
        $uid = $this->link->real_escape_string($uid);
        $image = $this->link->real_escape_string($image);
        $text = $this->link->real_escape_string($text);
        $tags = $this->link->real_escape_string($tags);
        $this->link->query("INSERT INTO `photos` (Uid, Image, Text, Tags) VALUES ($uid, '$image','$text','$tags')");
    }

    public function get_photo_by_id($photo_id)
    {
        $photo_id = $this->link->real_escape_string($photo_id);
        $sql_result = $this->link->query(
            "SELECT `p`.*, `u`.`Name` FROM `photos` `p` LEFT JOIN `users` `u` on `u`.`ID` = `p`.`Uid` WHERE `p`.`ID` = '$photo_id'");
        if ($sql_result->num_rows) {
            return $sql_result->fetch_assoc();
        }
        return false;
    }

    public function get_photo_comments($photo_id)
    {
        $photo_id = $this->link->real_escape_string($photo_id);
        $sql_result = $this->link->query(
            "SELECT `c`.*, `u`.`Name` FROM `comments` `c` LEFT JOIN `users` `u` on `u`.`ID` = `c`.`Uid` 
                WHERE `c`.`Pid` = '$photo_id' ORDER BY `ID` DESC");
        if ($sql_result->num_rows) {
            return $sql_result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function add_comment($pid, $uid, $text)
    {
        $pid = $this->link->real_escape_string($pid);
        $uid = $this->link->real_escape_string($uid);
        $text = $this->link->real_escape_string($text);
        $date = date("Y-m-d");
        $this->link->query("INSERT INTO `comments` (Pid, Uid, Text, Post_date) VALUES ($pid, $uid, '$text', '$date')");
        $last_id = $this->link->insert_id;
        $inserted_comment = $this->link->query(
            "SELECT `c`.*, `u`.`Name` FROM `comments` `c` LEFT JOIN `users` `u` on `u`.`ID` = `c`.`Uid` WHERE `c`.`ID` = '$last_id'");
        return $inserted_comment->fetch_assoc();
    }

    public function delete_photo($photo_id)
    {
        $photo_id = $this->link->real_escape_string($photo_id);

        $result = $this->link->query("SELECT Image FROM `photos` WHERE ID = '$photo_id'");

        if ($result->num_rows > 0) {
            $photo = $result->fetch_assoc();
            $image = $photo['Image'];

            $this->link->query("UPDATE `photos` SET Image = NULL WHERE ID = '$photo_id'");

            unlink($image);
        }
    }

    public function update_photo($photo_id, $image, $text, $tags)
    {
        $photo_id = $this->link->real_escape_string($photo_id);
        $image = $this->link->real_escape_string($image);
        $text = $this->link->real_escape_string($text);
        $tags = $this->link->real_escape_string($tags);

        if (!empty($image)) {
            $image = $this->link->real_escape_string($image);

            $this->link->query("UPDATE `photos` SET Image = '$image', Text = '$text', Tags = '$tags' WHERE ID = '$photo_id'");
        } else {
            $this->link->query("UPDATE `photos` SET Text = '$text', Tags = '$tags' WHERE ID = '$photo_id'");
        }
    }

    public function delete_record($recordId)
    {
        $recordId = $this->link->real_escape_string($recordId);

        $this->link->query("DELETE FROM `comments` WHERE Pid = '$recordId'");

        $result = $this->link->query("SELECT Image FROM `photos` WHERE ID = '$recordId'");

        if ($result->num_rows > 0) {
            $record = $result->fetch_assoc();
            if (isset($record['Image'])) {
                $image = $record['Image'];
                if ($image !== '') {
                    unlink($image);
                }
            }

            $this->link->query("DELETE FROM `photos` WHERE ID = '$recordId'");
        }
    }


    public function get_all_id_photos()
    {

        $sql_result = $this->link->query("SELECT * FROM `photos` ORDER BY `ID` DESC");
        if ($sql_result->num_rows) {
            return $sql_result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

}