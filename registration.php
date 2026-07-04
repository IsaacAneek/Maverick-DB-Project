<!DOCTYPE html>
<html>

<head>
    <title>Maverick Registration</title>
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>

    <div class="login-container">

        <h1>Register</h1>

        <form method="POST" action="actions.php">

            <label for="userid">User ID</label>
            <input
                type="text"
                id="userid"
                name="userid">

            <label for="username">User Name</label>
            <input
                type="text"
                id="username"
                name="username">

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password">

            <button type="submit" name="action" value="register">
                Create Account
            </button>

        </form>

    </div>

</body>

</html>
