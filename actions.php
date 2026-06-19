<?php
require_once("db.php");
if(isset($_POST["action"])) {
    $action = $_POST["action"];
    $column_name = '';

    if ($action == "add_todo") {
        $column_name = 'todo';
    }

    header('Location: index.php');
    exit();
}

header('Location: index.php');
exit();
?>