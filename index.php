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
        <div>
            <button>Spaces</button>
            <ul>
                <li>Space A</li>
                <li>Space B</li>
                <li>Space C</li>
            </ul>
        </div>
    </div>

    <div class="main">

        <div class="column">
            <h2>Todo</h2>

            <form action="actions.php" method="post">
                <button name="action" value="add_todo">
                    Add Row
                </button>
            </form>

            <div class="rows"></div>
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
