<?php
require_once("db.php");
function executeSQL($conn, $sql)
{
    $stid = oci_parse($conn, $sql);

    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        die($e['message']);
    }

    oci_free_statement($stid);
}


executeSQL($conn, "

    CREATE TABLE users (

    user_id NUMBER PRIMARY KEY,

    username VARCHAR2(100) NOT NULL UNIQUE,

    password_hash VARCHAR2(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL

)

");



executeSQL($conn, "

    CREATE TABLE spaces (

    space_id NUMBER PRIMARY KEY,

    user_id NUMBER NOT NULL,

    space_name VARCHAR2(100) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_space_user
    FOREIGN KEY(user_id)
    REFERENCES users(user_id)
    ON DELETE CASCADE,

    CONSTRAINT uq_space
    UNIQUE(user_id, space_name)

)

");

?>