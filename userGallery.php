<?php
include("database.php");
session_start();
$thisId = $_SESSION["id"];
$errorMessage = "";

$result = mysqli_query(
    $conn,
    "SELECT first_name, last_name FROM users WHERE id='$thisId'"
);
$resultNew = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submitImage"])) {
        $imageUrl = mysqli_real_escape_string($conn, $_POST["imageUrl"]);
        $imageUrl = filter_input(INPUT_POST, "imageUrl", FILTER_VALIDATE_URL);

        if ($imageUrl) {
            $check = mysqli_fetch_assoc(mysqli_query(
                $conn,
                "SELECT * FROM images WHERE user_id ='$thisId' AND url='$imageUrl'"
            ));

            if ($check == 0) {
                mysqli_query($conn, "INSERT INTO images (url, user_id) VALUES ('$imageUrl', '$thisId')");
            }
        } else {
            $errorMessage =  "Invalid URL!";
        }
    }
}

$galleryResult = mysqli_query($conn, "SELECT * FROM images WHERE user_id='$thisId'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/userGallery.css">
</head>

<body>
    <header>
        <h1>WebGallery</h1>
        <?php
        echo "<h3>{$resultNew["first_name"]} {$resultNew["last_name"]}'s gallery</h3>"
        ?>
        <a href="logout.php">
            <button>Log Out</button>
        </a>
    </header>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="imageUrl">Image URL:</label>
        <input type="text" name="imageUrl" id="imageUrl">

        <input type="submit" name="submitImage" value="Submit Image">
        <span style="color: red; margin: 15px; margin-top: 25px;"><?php echo $errorMessage; $errorMessage = ""?></span>
    </form>

    <div class="gallery">
        <?php
        while ($image = mysqli_fetch_assoc($galleryResult)) {
            if (!empty($image['url'])) {
                echo "<img src='{$image['url']}' alt='Gallery Image'>";
            }
        }
        ?>
    </div>

</body>

</html>