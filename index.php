<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

function load_spaces($conn, $user_id)
{
    $spaces = [];

    $sql = "SELECT *
            FROM spaces
            WHERE user_id = :user_id
            ORDER BY space_name";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":user_id", $user_id);

    oci_execute($stmt);

    while ($row = oci_fetch_assoc($stmt)) {
        $spaces[] = $row;
    }

    oci_free_statement($stmt);

    return $spaces;
}

function get_selected_space($spaces)
{
    if (isset($_GET["space_id"])) {
        return $_GET["space_id"];
    }

    if (!empty($spaces)) {
        return $spaces[0]["SPACE_ID"];
    }

    return null;
}

function load_kanban_tasks($conn, $space_id)
{
    $todo_tasks = [];
    $ongoing_tasks = [];
    $done_tasks = [];

    if ($space_id == null) {
        return [
            "todo" => $todo_tasks,
            "ongoing" => $ongoing_tasks,
            "done" => $done_tasks
        ];
    }

    $sql = "
    SELECT kt.*
    FROM kanban_tasks kt
    JOIN kanban_boards kb
        ON kt.kanban_board_id = kb.kanban_board_id
    WHERE kb.space_id = :space_id
    ORDER BY kt.position";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":space_id", $space_id);

    oci_execute($stmt);

    while ($task = oci_fetch_assoc($stmt)) {

        switch (strtolower($task["COLUMN_NAME"])) {

            case "todo":
                $todo_tasks[] = $task;
                break;

            case "ongoing":
                $ongoing_tasks[] = $task;
                break;

            case "done":
                $done_tasks[] = $task;
                break;
        }
    }

    oci_free_statement($stmt);

    return [
        "todo" => $todo_tasks,
        "ongoing" => $ongoing_tasks,
        "done" => $done_tasks
    ];
}

$spaces = load_spaces($conn, $user_id);

$selected_space_id = get_selected_space($spaces);

$tasks = load_kanban_tasks($conn, $selected_space_id);

$todo_tasks = $tasks["todo"];
$ongoing_tasks = $tasks["ongoing"];
$done_tasks = $tasks["done"];
?>

<!DOCTYPE html>

<html>

<head>
    <title>Maverick</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <div class="navbar">
        <h1>Maverick</h1>

        <div class="nav-controls">
            <div class="search-group">
                <input type="text" class="search-input" placeholder="Search tasks">

                <details class="dropdown">
                    <summary class="nav-action">Filter</summary>
                    <div class="dropdown-panel">
                        <label for="time-range">Time Range</label>
                        <input type="datetime-local" id="time-range" name="time_range">

                        <label for="task-name">Task Name</label>
                        <input type="text" id="task-name" name="task_name" placeholder="Task Name">

                        <label for="task-type">Task Type</label>
                        <input type="text" id="task-type" name="task_type" placeholder="Task Type">
                    </div>
                </details>

                <details class="dropdown">
                    <summary class="nav-action">Sort</summary>
                    <div class="dropdown-panel">
                        <label class="checkbox-row"><input type="checkbox" name="sort_time" checked> Time</label>
                        <label class="checkbox-row"><input type="checkbox" name="sort_name"> Name</label>
                        <label class="checkbox-row"><input type="checkbox" name="sort_type"> Type</label>
                    </div>
                </details>
            </div>
        </div>

        <div>
            <form action="actions.php" method="post">
                <button name="action" value="notifications">Notifications</button>
                <button name="action" value="help">Help</button>
                <button name="action" value="settings">Settings</button>
                <button name="action" value="profile">Profile</button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="sidebar">
            <form action="actions.php" method="post">
                <div class="siderbar-header">
                    <div class="siderbar-header-buttons">
                        <button type="button">Spaces</button>
                        <button type="submit" name="action" value="add_space">
                            Add New Space
                        </button>
                    </div>
                    <div class="siderbar-header-input">
                        <input type="text" name="space_name" placeholder="New Space Name" required>
                    </div>
                </div>
            </form>

            <div class="space">
                <?php foreach ($spaces as $space): ?>
                    <form action="index.php" method="get">
                        <input type="hidden" name="space_id" value="<?php echo $space["SPACE_ID"]; ?>">
                        <button type="submit">
                            <?php echo htmlspecialchars($space["SPACE_NAME"]); ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="main">
            <div class="column">
                <h2>Todo</h2>
                <form action="actions.php" method="post">
                    <input type="hidden" name="space_id" value="<?php echo $selected_space_id; ?>">
                    <input type="text" name="task_name" placeholder="Task name" required>
                    <button name="action" value="add_todo">
                        Add Row
                    </button>
                </form>

                <div class="rows">
                    <?php foreach ($todo_tasks as $task): ?>
                        <div class="task-card">
                            <p>
                                Task name :
                                <?php echo htmlspecialchars($task["TASK_NAME"]); ?>
                            </p>
                            <p>
                                Deadline :
                                <?php echo htmlspecialchars($task["DEADLINE"]); ?>
                            </p>
                            <p>
                                Created at :
                                <?php echo htmlspecialchars($task["CREATED_AT"]); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <div class="column">
                <div class="column">
                    <h2>In Progress</h2>

                    <form action="actions.php" method="post">
                        <input type="hidden" name="space_id" value="<?php echo $selected_space_id; ?>">

                        <input type="text" name="task_name" placeholder="Task name" required>

                        <button name="action" value="add_progress">
                            Add Row
                        </button>
                    </form>

                    <div class="rows">
                        <?php foreach ($ongoing_tasks as $task): ?>
                            <div class="task-card">
                                <p>
                                    Task name :
                                    <?php echo htmlspecialchars($task["TASK_NAME"]); ?>
                                </p>

                                <p>
                                    Deadline :
                                    <?php echo htmlspecialchars($task["DEADLINE"]); ?>
                                </p>

                                <p>
                                    Created at :
                                    <?php echo htmlspecialchars($task["CREATED_AT"]); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="column">
                    <h2>Done</h2>

                    <form action="actions.php" method="post">
                        <input type="hidden" name="space_id" value="<?php echo $selected_space_id; ?>">

                        <input type="text" name="task_name" placeholder="Task name" required>

                        <button name="action" value="add_done">
                            Add Row
                        </button>
                    </form>

                    <div class="rows">
                        <?php foreach ($done_tasks as $task): ?>
                            <div class="task-card">
                                <p>
                                    Task name :
                                    <?php echo htmlspecialchars($task["TASK_NAME"]); ?>
                                </p>

                                <p>
                                    Deadline :
                                    <?php echo htmlspecialchars($task["DEADLINE"]); ?>
                                </p>

                                <p>
                                    Created at :
                                    <?php echo htmlspecialchars($task["CREATED_AT"]); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>