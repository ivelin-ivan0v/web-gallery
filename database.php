<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "webgallery_db";
    $conn = "";

    try{
        $conn = mysqli_connect(
        $db_server,
        $db_user,
        $db_pass,
        $db_name);
    }
    catch(mysqli_sql_exception){
        echo "Error connecting to the database! <br>";
    }
 
    // $sql = "INSERT INTO users (email, password, first_name, last_name) VALUES ('$email', '$hash', 'Ivan', 'Ivanov')";
    //             mysqli_query($conn, $sql);

?>