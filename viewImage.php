<?php
include("database.php");
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$thisId = $_SESSION["id"];

$result = mysqli_query($conn, "SELECT first_name, last_name FROM users WHERE id='$thisId'");
$resultNew = mysqli_fetch_assoc($result);

if (empty($resultNew)) {
    $resultNew = ["first_name" => "User", "last_name" => ""];
}

if (isset($_GET['url'])) {
    $imageUrl = urldecode($_GET['url']); 
    $imageUrl = mysqli_real_escape_string($conn, $imageUrl);

    $query = mysqli_query($conn, "SELECT * FROM images WHERE url='$imageUrl' AND user_id='$thisId'");
    $image = mysqli_fetch_assoc($query);

    if (empty($image)) {
        echo "Image not found!";
        exit();
    }

    $uploadDate = $image['upload_date'];
} else {
    header("Location: userGallery.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteImage'])) {
    mysqli_query($conn, "DELETE FROM images WHERE url='$imageUrl' AND user_id='$thisId'");
    header("Location: userGallery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Image</title>
        <link rel="stylesheet" href="styles/userGallery.css">
    </head>
    <body>
        <header>
            <h1>WebGallery</h1>
            <h3><?php echo "{$resultNew["first_name"]} {$resultNew["last_name"]}'s gallery"; ?></h3>
            <a href="logout.php">
                <button>Log Out</button>
            </a>
        </header>

        <div class="image-viewer">
            <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="Selected Image">
            <p><strong>Uploaded on:</strong> <?php echo date("F j, Y, g:i a", strtotime($uploadDate)); ?></p>
            
            <form method="post">
                <button type="submit" name="deleteImage">Delete Image</button>
            </form>
            <a href="userGallery.php"><button>Go Back to Gallery</button></a>
        </div>
    </body>
</html>