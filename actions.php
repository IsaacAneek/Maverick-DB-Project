<?php
require_once("db.php");

if(isset($_POST["action"])) {
    $action = $_POST["action"];
    $column_name = '';

    if ($action == "add_space") {
        $space_name = trim($_POST["space_name"]);
        
        if (!empty($space_name)) {
            $user_id = 1; 

            $statement = $conn->prepare("INSERT INTO spaces (user_id, space_name) VALUES (?, ?)");
            $statement->bind_param("is", $user_id, $space_name);
            $statement->execute();
            $statement->close();
        }
    }

    if ($action == "add_todo") {
        $column_name = 'todo';
    }

    header('Location: index.php');
    exit();
}

header('Location: index.php');
exit();
?>