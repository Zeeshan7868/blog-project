<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

require('../fpdf/fpdf.php');


function send_request_email($recipientEmail, $recipientName, $subject, $body)
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;

    $mail->Username = '74shanali@gmail.com';
    $mail->Password = 'mmma euax chbu byes';

    $mail->setFrom('74shanali@gmail.com', 'Admin');
    $mail->addAddress($recipientEmail, $recipientName);

    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $body;

    $mail->send();
}

function send_pdf_and_email($recipientEmail, $recipientName, $subject, $body, $attachment_path)
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;

    $mail->Username = '74shanali@gmail.com';
    $mail->Password = 'mmma euax chbu byes';

    $mail->setFrom('74shanali@gmail.com', 'Admin');
    $mail->addAddress($recipientEmail, $recipientName);

    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $body;
    $mail->addAttachment($attachment_path);

    $mail->send();
    
    
}


function register_user($connection, $first_name, $last_name, $email, $password, $gender, $dob, $profile_image_path = NULL, $address)
{
    $query = "INSERT INTO user (
                role_id, first_name, last_name, email, password, gender, date_of_birth, user_image, address, is_active
              ) VALUES (
                2, '$first_name', '$last_name', '$email', '$password', '$gender', '$dob', '$profile_image_path', '$address', 'InActive')";

    $result = mysqli_query($connection, $query);

    return $result;
}

function add_user($connection, $first_name, $last_name, $email, $password, $gender, $dob, $profile_image_path = NULL, $address, $is_active)
{
    $query = "INSERT INTO user (
                role_id, first_name, last_name, email, password, gender, date_of_birth, user_image, address, is_approved, is_active
              ) VALUES (
                2, '$first_name', '$last_name', '$email', '$password', '$gender', '$dob', '$profile_image_path', '$address', 'Approved' , '$is_active')";

    $result = mysqli_query($connection, $query);

    return $result;
}


function login_user($connection, $email, $password)
{
    $query = "SELECT * FROM user 
              WHERE email = '$email' AND password = '$password'";

    $result = mysqli_query($connection, $query);
    return $result;
}

function check_email_exists($connection, $email)
{
    $email = trim($email);

    $query = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    return $result;
}
function update_user_status($connection, $user_id, $activeValue)
{

    $isActive = ($activeValue == "Active") ? "InActive" : "Active";
    $query = "UPDATE USER SET is_active = '$isActive' , updated_at = NOW() WHERE user_id = " . $user_id;
    $result = mysqli_query($connection, $query);

    return $result;
}

function update_approve_status($connection, $user_id, $approval_value)
{

    $is_approved = ($approval_value == "approve") ? "Approved" : "Rejected";

    $query = "UPDATE USER SET is_approved = '$is_approved', updated_at = NOW() WHERE user_id = " . $user_id;
    $result = mysqli_query($connection, $query);

    return $result;
}

function show_users($connection)
{
    $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`";
    $result = mysqli_query($connection, $query);

    return $result;
}

function user_details_pdf($email, $password)
{
    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'User Login Details', 0, 1, 'C');
    $pdf->Ln(10);


    $pdf->SetFont('Arial', '', 12);


    $pdf->Cell(40, 10, 'Email:');
    $pdf->Cell(100, 10, $email);
    $pdf->Ln(10);


    $pdf->Cell(40, 10, 'Password:');
    $pdf->Cell(100, 10, $password);
    $pdf->Ln(20);


    $filePath = '../pdfs/' . time() . '_user_details.pdf';


    if (!is_dir('../pdfs/')) {
        mkdir('../pdfs/', true);
    }

    $pdf->Output('F', $filePath);

    return $filePath;
}

function select_all_from_user_id($connection, $user_id)
{
    $query = "SELECT * FROM user WHERE user_id = $user_id";
    $result = mysqli_query($connection, $query);
    return $result;
}

function select_all_from_email($connection, $email)
{
    $query = "SELECT * FROM user WHERE email = $email";
    $result = mysqli_query($connection, $query);
    return $result;
}

// function session_maintainance()
// {
    
//     if ( isset($_SESSION['user']['email']) AND $_SESSION['user']['role_id'] == 2) {
//         header("location:../index.php");
//         die();

//     }
// }

// 1) Handle Forgot Password Request
// if (isset($_POST['send_password'])) {
//     // Grab raw email
//     $email = $_POST['email'];

//     // Rate‑limit via cookie (5 minutes)
//     if (isset($_COOKIE['forgot_timer']) && $_COOKIE['forgot_timer'] > time()) {
//         header(
//             'Location: ../forgot_password.php?msg='
//             . urlencode("Please wait before requesting again.")
//         );
//         exit;
//     }

//     // Query directly
//     $query  = "SELECT first_name, password FROM user WHERE email = '$email'";
//     $result = mysqli_query($connection, $query);

//     // Always set timer to prevent enumeration
//     setcookie('forgot_timer', time() + 300, time() + 300, '/');

//     $msg = "If that email exists, you will receive instructions shortly.";

//     if (mysqli_num_rows($result) === 0) {
//         header('Location: ../forgot_password.php?msg=' . urlencode($msg));
//         exit;
//     }

//     $user    = mysqli_fetch_assoc($result);
//     $subject = 'Password Recovery';
//     $body    = "Hello {$user['first_name']},<br><br>"
//              . "Your password is: <strong>{$user['password']}</strong><br><br>"
//              . "If you didn't request this, please contact support.";

//     send_request_email($email, $user['first_name'], $subject, $body);

//     header('Location: ../forgot_password.php?msg=' . urlencode($msg));
//     exit;
// }
