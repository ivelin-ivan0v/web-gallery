<?php
include("database.php");
session_start();

if(isset($_SESSION["id"])){
    header("Location: userGallery.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Gallery</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <header>
        <h1>WebGallery</h1>
    </header>

    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>User Login</h2>
        <label>Email:</label><br>
        <input type="email" name="email"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br>
        <input class="submitButton" type="submit" name="submit" value="log in"><br>
        <a class="register-link" href="registration.php">Don't have an account? Register here!</a>
    </form>
    <br>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

    if (isset($_POST["submit"])) {
        if (empty($email)) {
            echo "<span style='color: red;'>Please enter a valid email!<span/>";
        } elseif (empty($password)) {
            echo "<span style='color: red;'>Please enter a valid password!<span/>";
        } else {
            $result = mysqli_query(
                $conn,
                "SELECT id, password FROM users WHERE email='$email'"
            );

            $userInfo = mysqli_fetch_assoc($result);

            if (mysqli_num_rows($result) == 0) {
                echo "<span style='color: red;'>User with this email doesn't exist!<span/>";
            } elseif (password_verify($password, $userInfo["password"])) {
                $_SESSION["id"] = $userInfo["id"];
                header("Location: userGallery.php");
            } else {
                echo "<span style='color: red;'>Invalid password!<span/>";
            }
        }
    }
}

mysqli_close($conn);
?>