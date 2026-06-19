<!DOCTYPE html>

<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h1>My Dashboard</h1>

    <div>
        <form action="actions.php" method="post">
            <button name="action" value="home">Home</button>
            <button name="action" value="save">Save</button>
            <button name="action" value="settings">Settings</button>
        </form>
    </div>
</div>

<div class="content">

    <div class="sidebar">

        <div>
            <button>Projects</button>
            <ul>
                <li>Project A</li>
                <li>Project B</li>
                <li>Project C</li>
            </ul>
        </div>

        <div>
            <button>Tasks</button>
            <ul>
                <li>Task 1</li>
                <li>Task 2</li>
                <li>Task 3</li>
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
