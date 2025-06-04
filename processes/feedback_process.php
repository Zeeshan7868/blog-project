<?php
session_start();
require("../require/db_connection/connection.php");

if (isset($_POST['submit'])) {
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $feedback = $_POST['feedback'];

        $query = "INSERT INTO user_feedback (user_id, user_name, user_email, feedback) VALUES ($user_id, '$username', '$email', '$feedback')";
    } else {
        
        $username = $_POST['username'];
        $email = $_POST['email'];
        $feedback = $_POST['feedback'];

        $query = "INSERT INTO user_feedback (user_name, user_email, feedback) VALUES ('$username', '$email', '$feedback')";
    }

    $result = mysqli_query($connection, $query);

    if ($result) {
        header("Location: ../contact.php?msg=Feedback submitted successfully.");
        die();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    header("Location: ../contact.php");

}
?>
