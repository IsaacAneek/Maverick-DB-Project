<?php
require_once("db.php");

function db_error($resource)
{
    $e = oci_error($resource);
    die($e['message']);
}

function add_space($conn)
{
    $space_name = trim($_POST["space_name"]);

    if (empty($space_name)) {
        return;
    }

    $user_id = 1;
    $space_id = time();

    $sql = "INSERT INTO spaces (space_id, user_id, space_name)
            VALUES (:space_id, :user_id, :space_name)";

    $statement = oci_parse($conn, $sql);

    if (!$statement) {
        db_error($conn);
    }

    oci_bind_by_name($statement, ":space_id", $space_id);
    oci_bind_by_name($statement, ":user_id", $user_id);
    oci_bind_by_name($statement, ":space_name", $space_name);

    if (!oci_execute($statement, OCI_COMMIT_ON_SUCCESS)) {
        db_error($statement);
    }

    oci_free_statement($statement);
}

function add_todo($conn)
{
}

if (isset($_POST["action"])) {

    switch ($_POST["action"]) {

        case "add_space":
            add_space($conn);
            break;

        case "add_todo":
            add_todo($conn);
            break;
    }

    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
?>