<?php

echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

session_start();
require("../require/db_connection/connection.php");
require("../functions/user_functions.php");

// require("processes/user_process.php");

if (isset($_POST['send_password'])) {
    $email = $_POST['email'];
    echo $email;

    $query  = "SELECT first_name, password FROM user WHERE email = '$email'";
    $result = mysqli_query($connection, $query);


    if ($result->num_rows > 0) {

        setcookie('forgot_timer', time() + 300, time() + 300, '/');

        $user    = mysqli_fetch_assoc($result);
        $subject = 'Password Recovery';
        $body    = "Hello " . $user['first_name'] . "<br><br>" . "Your password is: <strong>" . $user['password'] . "</strong><br><br>";

        send_request_email($email, $user['first_name'], $subject, $body);
        header('Location: ../forgot_password.php?msg=Please check your email. If you did not receive it, contact Admin.');
    } else {
        header("location:../forgot_password.php?msg=This Email is not registered!...");
    }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "approval") {

    $user_id = $_REQUEST['user_id'];
    $approval_value = $_REQUEST['approval_value'];

    $result = update_approve_status($connection, $user_id, $approval_value);
    $user_result = select_all_from_user_id($connection, $user_id);

    if (!$result) {
        echo "Failed to update Approved status!...";
        die();
    }

    if ($user_result->num_rows > 0) {
        $user = mysqli_fetch_assoc($user_result);

        $email = $user['email'];
        $first_name = $user['first_name'];
        $password = $user['password'];

        if ($approval_value == "approve") {
            

            send_request_email($email, $first_name, "Account Approval notes_over_flow.com", "Your Account has been successfully approved Now You Can Login!... ");
        } else if ($approval_value == "reject") {
            send_request_email($email, $password, "Account Rejection on notes_over_flow.com", "Your Account has been rejected");
        }
        header("location:../admin/view_user.php?user_id=".$user_id);
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'is_active') {
    $user_id     = (int)$_POST['user_id'];
    $activeValue = $_POST['activeValue']; 

    
    $activeValueEscaped = mysqli_real_escape_string($connection, $activeValue);
    $updateQuery = "UPDATE user SET is_active = '$activeValueEscaped' WHERE user_id = $user_id";
    mysqli_query($connection, $updateQuery);

    
    $user = select_all_from_user_id($connection, $user_id)->fetch_assoc();
    if ($activeValue === 'Active') {
        send_request_email(
            $user['email'],
            $user['first_name'],
            'Account Activated - notesOverFlow.com',
            'Your account has been activated. You can now log in.'
        );
    } else {
        send_request_email(
            $user['email'],
            $user['first_name'],
            'Account Deactivated - notesOverFlow.com',
            'Your account has been deactivated. Please contact the admin to reactivate.'
        );
    }

    header("Location: ../admin/view_user.php?user_id=" . $user_id . "&msg=active_status_updated");
    
}
