CREATE OR REPLACE TYPE task_type AS OBJECT (
    task_id NUMBER,
    user_name VARCHAR2(255),
    task_name VARCHAR2(255),
    column_name VARCHAR2(20),
    created_at TIMESTAMP
);
/

CREATE OR REPLACE TYPE task_type_array AS TABLE OF task_type;
/

CREATE OR REPLACE PROCEDURE print_all_tasks
IS
    tasks_array task_type_array := task_type_array();
BEGIN

    FOR rec IN (
        SELECT kt.kanban_task_id,
        u.username,
        kt.task_name,
        kt.column_name,
        kt.created_at
        FROM kanban_tasks kt
        JOIN kanban_boards kb
        ON kt.kanban_board_id = kb.kanban_board_id
        JOIN spaces s
        ON kb.space_id = s.space_id
        JOIN users u
        ON s.user_id = u.user_id
    )
    LOOP
        tasks_array.EXTEND;

        tasks_array(tasks_array.LAST) := task_type(
            rec.kanban_task_id,
            rec.username,
            rec.task_name,
            rec.column_name,
            rec.created_at
        );
    END LOOP;

    FOR i IN 1 .. tasks_array.COUNT LOOP
        DBMS_OUTPUT.PUT_LINE(
            'User: ' || tasks_array(i).user_name || ' | Task: ' || tasks_array(i).task_name ||
            ' | Column: ' || tasks_array(i).column_name || ' | Created: ' || TO_CHAR(tasks_array(i).created_at, 'DD-MM-YYYY HH24:MI:SS')
        );
    END LOOP;
END;
/