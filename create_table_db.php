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

executeSQL($conn, "
    CREATE TABLE kanban_boards (
    kanban_board_id NUMBER PRIMARY KEY,
    space_id NUMBER NOT NULL UNIQUE,
    board_name VARCHAR2(100) DEFAULT 'Kanban Board' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_kanban_board_space
        FOREIGN KEY (space_id)
        REFERENCES spaces(space_id)
        ON DELETE CASCADE
);");

executeSQL($conn, "
    CREATE TABLE kanban_tasks (
    kanban_task_id NUMBER PRIMARY KEY,
    kanban_board_id NUMBER NOT NULL,
    task_name VARCHAR2(255) NOT NULL,

    column_name VARCHAR2(20) NOT NULL
        CHECK (column_name IN ('todo','ongoing','done')),

    deadline TIMESTAMP,

    is_completed NUMBER(1)
        DEFAULT 0
        CHECK (is_completed IN (0,1)),

    position NUMBER DEFAULT 0 NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_kanban_tasks_board
        FOREIGN KEY (kanban_board_id)
        REFERENCES kanban_boards(kanban_board_id)
        ON DELETE CASCADE
);");

executeSQL($conn, "
    CREATE TABLE eisenhower_matrices (
    eisenhower_matrix_id NUMBER PRIMARY KEY,
    space_id NUMBER NOT NULL UNIQUE,

    matrix_name VARCHAR2(100)
        DEFAULT 'Eisenhower Matrix'
        NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_eisenhower_space
        FOREIGN KEY (space_id)
        REFERENCES spaces(space_id)
        ON DELETE CASCADE
);");

executeSQL($conn, "
    CREATE TABLE eisenhower_tasks (
    eisenhower_task_id NUMBER PRIMARY KEY,
    eisenhower_matrix_id NUMBER NOT NULL,

    task_name VARCHAR2(255) NOT NULL,

    quadrant VARCHAR2(20) NOT NULL
        CHECK (
            quadrant IN (
                'important',
                'not_important',
                'urgent',
                'not_urgent'
            )
        ),

    deadline TIMESTAMP,

    is_completed NUMBER(1)
        DEFAULT 0
        CHECK (is_completed IN (0,1)),

    position NUMBER DEFAULT 0 NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_eisenhower_tasks
        FOREIGN KEY (eisenhower_matrix_id)
        REFERENCES eisenhower_matrices(eisenhower_matrix_id)
        ON DELETE CASCADE
);");

executeSQL($conn, "
    
    CREATE TABLE pomodoro_lists (
    pomodoro_list_id NUMBER PRIMARY KEY,

    space_id NUMBER NOT NULL UNIQUE,

    list_name VARCHAR2(100)
        DEFAULT 'Pomodoro Timer'
        NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_pomodoro_space
        FOREIGN KEY (space_id)
        REFERENCES spaces(space_id)
        ON DELETE CASCADE
);");

executeSQL($conn, "
    CREATE TABLE pomodoro_tasks (
    pomodoro_task_id NUMBER PRIMARY KEY,

    pomodoro_list_id NUMBER NOT NULL,

    task_name VARCHAR2(255) NOT NULL,

    work_duration_min NUMBER DEFAULT 25 NOT NULL,

    break_duration_min NUMBER DEFAULT 5 NOT NULL,

    sessions_completed NUMBER DEFAULT 0 NOT NULL,

    is_completed NUMBER(1)
        DEFAULT 0
        CHECK (is_completed IN (0,1)),

    position NUMBER DEFAULT 0 NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

    CONSTRAINT fk_pomodoro_tasks
        FOREIGN KEY (pomodoro_list_id)
        REFERENCES pomodoro_lists(pomodoro_list_id)
        ON DELETE CASCADE
);");

?>