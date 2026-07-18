<?php
require_once("db.php");
session_start();

function db_error($resource)
{
    $e = oci_error($resource);
    die($e['message']);
}

function add_space($conn)
{
    if (!isset($_SESSION["logged_in"])) {
        header("Location: login.php");
        exit();
    }

    $space_name = trim($_POST["space_name"]);

    if ($space_name == "") {
        return;
    }

    $user_id = $_SESSION["user_id"];

    $space_id = time();
    $kanban_board_id = $space_id + 1;

    $sql = "INSERT INTO spaces(space_id,user_id,space_name)
            VALUES(:space_id,:user_id,:space_name)";

    $stmt = oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":space_id",$space_id);
    oci_bind_by_name($stmt,":user_id",$user_id);
    oci_bind_by_name($stmt,":space_name",$space_name);

    oci_execute($stmt);

    oci_free_statement($stmt);

    $sql = "INSERT INTO kanban_boards
            (kanban_board_id,space_id)
            VALUES
            (:board_id,:space_id)";

    $stmt = oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":board_id",$kanban_board_id);
    oci_bind_by_name($stmt,":space_id",$space_id);

    oci_execute($stmt,OCI_COMMIT_ON_SUCCESS);

    oci_free_statement($stmt);

    header("Location:index.php?space_id=".$space_id);
    exit();
}



function add_progress($conn)
{
    $space_id = $_POST["space_id"];
    $task_name = trim($_POST["task_name"]);

    if ($task_name == "")
        return;

    $sql = "SELECT kanban_board_id
            FROM kanban_boards
            WHERE space_id = :space_id";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":space_id", $space_id);

    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    oci_free_statement($stmt);

    if (!$row)
        die("Board not found");

    $board_id = $row["KANBAN_BOARD_ID"];
    $task_id = time();

    $sql = "INSERT INTO kanban_tasks
            (
                kanban_task_id,
                kanban_board_id,
                task_name,
                column_name,
                position
            )
            VALUES
            (
                :task_id,
                :board_id,
                :task_name,
                'ongoing',
                0
            )";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":task_id", $task_id);
    oci_bind_by_name($stmt, ":board_id", $board_id);
    oci_bind_by_name($stmt, ":task_name", $task_name);

    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    oci_free_statement($stmt);

    header("Location:index.php?space_id=".$space_id);
    exit();
}

function add_done($conn)
{
    $space_id = $_POST["space_id"];
    $task_name = trim($_POST["task_name"]);

    if ($task_name == "")
        return;

    $sql = "SELECT kanban_board_id
            FROM kanban_boards
            WHERE space_id = :space_id";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":space_id", $space_id);

    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    oci_free_statement($stmt);

    if (!$row)
        die("Board not found");

    $board_id = $row["KANBAN_BOARD_ID"];
    $task_id = time();

    $sql = "INSERT INTO kanban_tasks
            (
                kanban_task_id,
                kanban_board_id,
                task_name,
                column_name,
                position
            )
            VALUES
            (
                :task_id,
                :board_id,
                :task_name,
                'done',
                0
            )";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":task_id", $task_id);
    oci_bind_by_name($stmt, ":board_id", $board_id);
    oci_bind_by_name($stmt, ":task_name", $task_name);

    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    oci_free_statement($stmt);

    header("Location:index.php?space_id=".$space_id);
    exit();
}

function add_todo($conn)
{
    $space_id=$_POST["space_id"];
    $task_name=trim($_POST["task_name"]);

    if($task_name=="")
        return;

    $sql="SELECT kanban_board_id
          FROM kanban_boards
          WHERE space_id=:space_id";

    $stmt=oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":space_id",$space_id);

    oci_execute($stmt);

    $row=oci_fetch_assoc($stmt);

    oci_free_statement($stmt);

    if(!$row)
        die("Board not found");

    $board_id=$row["KANBAN_BOARD_ID"];

    $task_id=time();

    $sql="INSERT INTO kanban_tasks (
        kanban_task_id,
        kanban_board_id,
        task_name,
        column_name,
        position
    ) VALUES (
        :task_id,
        :board_id,
        :task_name,
        'todo',
        0
    )";

    $stmt=oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":task_id",$task_id);
    oci_bind_by_name($stmt,":board_id",$board_id);
    oci_bind_by_name($stmt,":task_name",$task_name);

    oci_execute($stmt,OCI_COMMIT_ON_SUCCESS);

    oci_free_statement($stmt);

    header("Location:index.php?space_id=".$space_id);
    exit();
}


function login($conn)
{
    
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT user_id, username, password_hash
            FROM users
            WHERE username = :username";

    $statement = oci_parse($conn, $sql);

    if (!$statement) {
        db_error($conn);
    }
    

    oci_bind_by_name($statement, ":username", $username);

    if (!oci_execute($statement)) {
        db_error($statement);
    }

    $user = oci_fetch_assoc($statement);
    //var_dump($user);


    oci_free_statement($statement);

    if (!$user) {
        die("Invalid username");
    }

    if (!password_verify($password, $user["PASSWORD_HASH"])) {
        die("Invalid password");
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["USER_ID"];
    $_SESSION["username"] = $user["USERNAME"];
    $_SESSION["logged_in"] = true;


    echo "<script>alert('Login Successfull')</script>";

    header("Location: index.php");
    exit();
}

function register($conn)
{
    $user_id = trim($_POST["userid"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($user_id) || empty($username) || empty($password)) {
        die("All fields are required.");
    }

    $check_sql = "SELECT user_id
                  FROM users
                  WHERE username = :username";

    $check_stmt = oci_parse($conn, $check_sql);

    if (!$check_stmt) {
        db_error($conn);
    }

    oci_bind_by_name($check_stmt, ":username", $username);

    if (!oci_execute($check_stmt)) {
        db_error($check_stmt);
    }

    if (oci_fetch_assoc($check_stmt)) {
        oci_free_statement($check_stmt);
        // header("Location: registration.php");
        echo "<script>alert('Username already exists');window.location.href = 'registration.php';</script>";
        exit();
    }

    oci_free_statement($check_stmt);

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
            (user_id, username, password_hash)
            VALUES
            (:user_id, :username, :password_hash)";

    $statement = oci_parse($conn, $sql);

    if (!$statement) {
        db_error($conn);
    }

    oci_bind_by_name($statement, ":user_id", $user_id);
    oci_bind_by_name($statement, ":username", $username);
    oci_bind_by_name($statement, ":password_hash", $password_hash);

    if (!oci_execute($statement, OCI_COMMIT_ON_SUCCESS)) {
        db_error($statement);
    }

    oci_free_statement($statement);

    echo "<script>alert('Registration Successful');</script>";

    header("Location: login.php");
    exit();
}

function update_task($conn)
{
    if (!isset($_SESSION["logged_in"])) {
        header("Location: login.php");
        exit();
    }

    $task_id = $_POST["task_id"];
    $space_id = $_POST["space_id"];
    $task_name = trim($_POST["new_task_name"]);

    if ($task_name == "") {
        header("Location: index.php?space_id=" . $space_id);
        exit();
    }

    $sql = "UPDATE kanban_tasks
            SET task_name = :task_name
            WHERE kanban_task_id = :task_id";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":task_name", $task_name);
    oci_bind_by_name($stmt, ":task_id", $task_id);

    if (!oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
        db_error($stmt);
    }

    oci_free_statement($stmt);

    header("Location: index.php?space_id=" . $space_id);
    exit();
}

function logout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];
    session_destroy();

    header("Location: login.php");
    exit();
}

if (isset($_POST["action"])) {

    switch ($_POST["action"]) {
        case "register":
            register($conn);
            break;

        case "login":
            login($conn);
            break;

        case "add_space":
            add_space($conn);
            break;

        case "add_todo":
            add_todo($conn);
            break;

        case "add_progress":
            add_progress($conn);
            break;

        case "add_done":
            add_done($conn);
            break;

        case "update_task":
            update_task($conn);
            break;
        case "logout":
            logout();
            break;
    }

    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
?>