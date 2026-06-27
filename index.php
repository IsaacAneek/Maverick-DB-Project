<?php
require_once 'db.php';

$sql = "SELECT * FROM kanban_tasks ORDER BY position ASC";
$statement = oci_parse($conn, $sql);
oci_execute($statement);

$todo_tasks = [];
$ongoing_tasks = [];
$done_tasks = [];

while (($task = oci_fetch_assoc($statement)) != false) {
    if (strtolower($task['COLUMN_NAME']) === 'todo')
        $todo_tasks[] = $task;
    if (strtolower($task['COLUMN_NAME']) === 'ongoing')
        $ongoing_tasks[] = $task;
    if (strtolower($task['COLUMN_NAME']) === 'done')
        $done_tasks[] = $task;
}

$spaces = [];
$space_sql = "SELECT * FROM spaces ORDER BY space_id ASC";
$space_statement = oci_parse($conn, $space_sql);
oci_execute($space_statement);

while (($space = oci_fetch_assoc($space_statement)) != false) {
    $spaces[] = $space;
}

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
                <div>
                    <div class="siderbar-header">
                        <div class="siderbar-header-buttons">
                            <button name="action">Spaces</button>
                            <button name="action" value="add_space">Add New Space</button>
                        </div>
                        <div class="siderbar-header-input">
                            <input type="text" name="space_name" placeholder="New Space Name" required>
                        </div>
                    </div>

                    <div class="space">
                        <?php foreach ($spaces as $space): ?>
                            <button type="button">
                                <?php echo htmlspecialchars($space['space_name']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="main">

            <div class="column">
                <h2>Todo</h2>

                <form action="actions.php" method="post">
                    <button name="action" value="add_todo">
                        Add Row
                    </button>
                </form>

                <div class="rows">
                    <?php foreach ($todo_tasks as $task): ?>
                        <div class="task-card">
                            <?php echo htmlspecialchars($task['task_name']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="column">
                <h2>In Progress</h2>

                <form action="actions.php" method="post">
                    <button name="action" value="add_progress">
                        Add Row
                    </button>
                </form>

                <div class="rows"></div>
            </div>

            <div class="column">
                <h2>Done</h2>

                <form action="actions.php" method="post">
                    <button name="action" value="add_done">
                        Add Row
                    </button>
                </form>

                <div class="rows"></div>
            </div>

        </div>

    </div>

</body>

</html>