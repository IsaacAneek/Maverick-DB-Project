<!DOCTYPE html>
<html>

<head>
    <title>Maverick</title>
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>

    <div class="login-container">

        <h1>Maverick</h1>

        <form method="POST" action="actions.php">

            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username">

            <label for="userid">ID/Roll</label>
            <input 
                type="text"
                id="userid"
                name="userid">

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password">

            <div class="row">
                <label class="remember">
                    <input
                        type="checkbox"
                        name="remember">
                    Remember Me
                </label>

                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit">
                Login
            </button>

        </form>

    </div>

</body>

</html>