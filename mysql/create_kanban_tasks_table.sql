CREATE TABLE kanban_tasks (
    kanban_task_id  INT AUTO_INCREMENT PRIMARY KEY,
    task_name       VARCHAR(255) NOT NULL,
    column_name     VARCHAR(50) NOT NULL CHECK (column_name IN ('todo', 'ongoing', 'done')),
    deadline        DATETIME NULL,
    position        INT NOT NULL DEFAULT 0
);