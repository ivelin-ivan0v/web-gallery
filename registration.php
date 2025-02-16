<?php
include("database.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Web Gallery</title>
        <link rel="stylesheet" href="styles/registration.css">
    </head>

    <body>
        <header>
            <h1>WebGallery</h1>
        </header>

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h2>Create new account</h2>
            <label>Email:</label><br>
            <input type="email" name="email"><br>
            <label>Password:</label><br>
            <input type="password" name="password"><br>
            <label>First Name:</label><br>
            <input type="text" name="first_name"><br>
            <label>Last Name:</label><br>
            <input type="text" name="last_name"><br>
            <input class="submitButton" type="submit" name="submit" value="Register"><br>
            <a class="register-link" href="index.php">Already have an account? Login here!</a>
        </form>
        <br>


    </body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $firstName = filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

    if (isset($_POST["submit"])) {
        if (empty($email)) {
            echo "<span style='color: red;'>Please enter a valid email!<span/>";
        } elseif (empty($password)) {
            echo "<span style='color: red;'>Please enter a valid password!<span/>";
        } elseif (empty($firstName)) {
            echo "<span style='color: red;'>Please enter a valid first name!<span/>";
        } elseif (empty($lastName)) {
            echo "<span style='color: red;'>Please enter a valid last name!<span/>";
        } else {
            $result = mysqli_query(
                $conn,
                "SELECT id, password FROM users WHERE email='$email'"
            );
            if (mysqli_num_rows($result) > 0) {
                echo "<span style='color: red;'>User with this email already exists!<span/>";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query(
                    $conn,
                    "INSERT INTO users (email, password, first_name, last_name) 
                    VALUES ('$email', '$hash', '$firstName', '$lastName');"
                );

                $resultNew = mysqli_query(
                    $conn,
                    "SELECT id FROM users WHERE email='$email'"
                );
                $userInfo = mysqli_fetch_assoc($resultNew);

                $_SESSION["id"] = $userInfo["id"];
                header("Location: userGallery.php");
            }
        }
    }
}
mysqli_close($conn);
?>