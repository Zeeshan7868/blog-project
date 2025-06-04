<?php
// echo "<pre>";
// print_r($_REQUEST);
// print_r($_FILES);
// echo "</pre>";



session_start();
require("../require/db_connection/connection.php");
require("../functions/user_functions.php");





if (isset($_REQUEST['register'])) {

    extract($_REQUEST);
    $flag = true;

    $name_pattern     =  '/^(?=.{3,}$)[A-Z][a-z]+(?: [A-Z][a-z]+)*$/';
    $email_pattern    =  '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/';
    $password_pattern =  '/^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/';

    $_SESSION['old'] = [
        'first_name' => $first_name   ?? '',
        'last_name'  => $last_name    ?? '',
        'email'      => $email        ?? '',
        'gender'     => $gender       ?? '',
        'dob'        => $dob          ?? '',
        'address'    => $address      ?? '',

    ];

    $errors = [
        'first_name'       => '',
        'last_name'        => '',
        'email'            => '',
        'gender'           => '',
        'dob'              => '',
        'profile_image'    => '',
        'address'          => '',
        'password'         => '',
        'confirm_password' => '',
    ];

    // 1) First Name
    if (empty($first_name)) {
        $flag = false;
        $errors['first_name'] = "Required Field";
    } elseif (!preg_match($name_pattern, $first_name)) {
        $flag = false;
        $errors['first_name'] = "Pattern Must Be Like eg: Zeeshan";
    }

    // 2) Last Name
    if (empty($last_name)) {
        $flag = false;
        $errors['last_name'] = "Required Field";
    } elseif (!preg_match($name_pattern, $last_name)) {
        $flag = false;
        $errors['last_name'] = "Pattern Must Be Like eg: Mallah";
    }

    // 3) Email
    if (empty($email)) {
        $flag = false;
        $errors['email'] = "Required Field";
    } elseif (!preg_match($email_pattern, $email)) {
        $flag = false;
        $errors['email'] = "Pattern Must Be Like eg: zeeshan123@gmail.com";
    } elseif (preg_match($email_pattern, $email)) {
        $query = "SELECT email from user where email = $email";
        $result = mysqli_query($connection, $query);
        if ($result->num_rows > 0) {
            $flag = false;
            $errors['email'] = "Email already Exists!..";
        }
    }

    // 4) Gender
    if (!isset($gender)) {
        $flag = false;
        $errors['gender'] = "Field Required";
    }

    // 5) DOB
    if (empty($dob)) {
        $flag = false;
        $errors['dob'] = "Field Required";
    } else {
        $today = new DateTime();
        $birthDate = new DateTime($dob);
        $age = $today->diff($birthDate)->y;

        if ($age <= 10) {
            $flag = false;
            $errors['dob'] = "Age must be equal greater than 10 years";
        }
    }

    // 6) Profile Image
    $profile_image_path = "MyFiles/default.png";
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png"];

        if (!in_array($ext, $allowed)) {
            $flag = false;
            $errors['profile_image'] = "Extensions should be JPG, JPEG, or PNG";
        } else {
            $folder = "MyFiles";
            if (!is_dir($folder)) {
                if (!mkdir($folder, true)) {
                    die("Couldn't Create Directory $folder");
                }
            }
            $newName = time() . "_" . $fileName;
            $uploadPath = "$folder/$newName";

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                $profile_image_path = "$folder/$newName";
            } else {
                $profile_image_path = "MyFiles/default.png";
            }
        }
    }

    // 7) Address
    if (empty($address)) {
        $flag = false;
        $errors['address'] = "Required Field";
    }

    // 8) Password
    if (empty($password)) {
        $flag = false;
        $errors['password'] = "Required Field";
    } elseif (!preg_match($password_pattern, $password)) {
        $flag = false;
        $errors['password'] = "Password must have at least 8 characters, a number, and a special character";
    }


    if (empty($confirm_password)) {
        $flag = false;
        $errors['confirm_password'] = "Required Field";
    } elseif ($password !== $confirm_password) {
        $flag = false;
        $errors['confirm_password'] = "Passwords do not match";
    }


    if ($flag == true) {

        $result = register_user($connection, $first_name, $last_name, $email, $password, $gender, $dob, $profile_image_path, $address);

        $current_user_email = $email;
        $current_user_first_name = $first_name;


        if ($result) {
            // send_request_email($email, $first_name, "Account Request To NotesOverFlow.com", "Your Account Have Been created, Now wait for admin to approve or reject Your account!...");
            $pdfPath = user_details_pdf($email, $password);

            $is_sent = send_pdf_and_email($email, $first_name, "Account Request To NotesOverFlow.com", "Your Account Have Been created, Now wait for admin to approve or reject Your account!...<br>Your Email: $email <br>Your Password: $password", $pdfPath);

            $query = "SELECT email,first_name FROM user where role_id = 1";
            $result = mysqli_query($connection, $query);
            if ($result->num_rows) {
                while ($row = mysqli_fetch_assoc($result)) {
                    extract($row);
                    send_request_email($email, $first_name, "Account request from user $current_user_first_name", "You Have account approval request from $current_user_email");
                }
            }


            if ($is_sent) {
                header("location:../login.php?msg=could not send varification email try again!...&is_sent=no");
            } else {
                header("Location: ../login.php?msg=Your account is registered, Now wait for admin to approve/reject, You Will be informed through an Email!...&pdf_link=myproject/" . $pdfPath . "&is_sent=yes");
            }
        } else {
            header("location:../register.php?msg=something went wrong!..&is_sent=no");
        }
        unset($_SESSION['old']);
        unset($_SESSION['errors']);
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: ../register.php");
    }
} else if (isset($_POST['login'])) {

    extract($_REQUEST);
    $result = login_user($connection, $email, $password);

    if ($result->num_rows) {
        $row = mysqli_fetch_assoc($result);
        if ($row['is_approved'] == "Rejected") {
            header("location:../login.php?msg=Your Account Has Been Rejected You Can't login!...");
            die();
        } else if ($row['is_approved'] == "Pending") {
            header("location:../login.php?msg=Your Account is Pending Wait for admin to approve!...");
            die();
        } else if ($row['is_active'] == "InActive") {
            header("location:../login.php?msg=Your Account is InActive Contact to Admin!...");
            die();
        }

        $_SESSION["user"] = $row;

        if ($row['role_id'] == 2) {
            header("Location: ../index.php");
        } elseif ($row['role_id'] == 1) {
            header("Location: ../admin/dashboard.php");
        }
    } else {
        header("Location: ../login.php?msg=Invalid email or Password");
    }
} else if (isset($_REQUEST['action']) and $_REQUEST['action'] == "show_users") {
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'all';
    if($type == "all") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`";
    } else if ($type == "admin") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.role_id = 1";
    } else if ($type == "all") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`";
    }
     else if ($type == "pending") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.is_approved = 'Pending'";
    }
     else if ($type == "approved") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.is_approved = 'Approved'";
    }
     else if ($type == "rejected") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.is_approved = 'Rejected'";
    }
     else if ($type == "active") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.is_active = 'Active'";
    }
     else if ($type == "inactive") {
        $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
                    JOIN role r
                    ON u.`role_id` = r.`role_id`
                    WHERE u.is_active = 'InActive'";
    }

    // $query = "SELECT *,u.`is_active` AS 'user_active' FROM USER u
    //                 JOIN role r
    //                 ON u.`role_id` = r.`role_id`";
    $result = mysqli_query($connection, $query);
?>
    <table id="blogsTable" class="table table-hover align-middle display nowrap" style="width:100%">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Role</th>
                <th>Approval</th>
                <th>Is Active</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

            // $result = show_users($connection);

            while ($row = mysqli_fetch_assoc($result)) {
                extract($row);

            ?>
                <tr>
                    <td><?= $user_id ?></td>
                    <td><a href="view_user.php?user_id=<?= $user_id ?>"> <?= $first_name . " " . $last_name ?></a></td>
                    <td><?= $email ?></td>
                    <td><?= $gender ?></td>
                    <td><?= $date_of_birth ?></td>
                    <td><?= $role_type ?></td>
                    <td><span class="badge bg-success" id="is_approved<?= $user_id ?>"><?= $is_approved ?></span></td>
                    <td><span class="badge bg-warning text-dark" id="is_active<?= $user_id ?>"><?= $user_active ?></span></td>
                    <td><?= date($created_at) ?></td>
                    <td><?= date($updated_at) ?></td>
                    <td>
                        <select name="approval<?= $user_id ?>" id="approval<?= $user_id ?>" onchange="approval(<?= $user_id ?>)">
                            <option value="">Approve/Reject</option>
                            <option value="approve">Approve</option>
                            <option value="reject">reject</option>
                        </select>
                        <button class="btn btn-sm btn-outline-info" onclick="active(<?= $user_id ?>)">
                            <?php
                            if ($user_active == "Active") {
                                echo "InActive";
                            } else {
                                echo "Active";
                            }
                            ?>
                        </button>
                        <a href="../admin/add_user.php?action=edit&user_id=<?= $user_id ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>

            <?php
            }
            ?>
        </tbody>
    </table>
<?php
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "approval") {

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
            // $pdfPath = user_details_pdf($email, $password);

            send_request_email($email, $first_name, "Account Approval notes_over_flow.com", "Your Account has been successfully approved Now You Can Login!... ");
        } else if ($approval_value == "reject") {
            send_request_email($email, $password, "Account Rejection on notes_over_flow.com", "Your Account has been rejected");
        }
    }
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "is_active") {
    $user_id = $_REQUEST['user_id'];
    $activeValue = $_REQUEST['activeValue'];

    // echo $user_id." ".$activeValue;

    $result = update_user_status($connection, $user_id, $activeValue);

    $user_result = select_all_from_user_id($connection, $user_id);

    $row = mysqli_fetch_assoc($user_result);
    extract($row);

    if (!$result) {
        echo "Failed to update Active status!...";
    } else if ($activeValue == "Active") {
        send_request_email($email, $first_name, "Account Activation notesOverFlow.com", "Your have been activated Now You can Login to Your Account!...");
    } else if ($activeValue == "InActive") {
        send_request_email($email, $first_name, "Account Deactivation notesOverFlow.com", "Your have been deactivated, Please contact to admin!...");
    }
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'checkemail') {
    $email = $_REQUEST['email'];

    $result = check_email_exists($connection, $email);

    if ($result->num_rows > 0) {
        echo "<span class='text-danger'>Email already exists</span>";
    } else {
        echo "";
    }
} elseif (isset($_REQUEST['add_user'])) {

    extract($_REQUEST);

    $profile_image_path = "MyFiles/default.png";
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png"];

        if (!in_array($ext, $allowed)) {
            $flag = false;
            $errors['profile_image'] = "Extensions should be JPG, JPEG, or PNG";
        } else {
            $folder = "MyFiles";
            if (!is_dir($folder)) {
                if (!mkdir($folder, true)) {
                    die("Couldn't Create Directory $folder");
                }
            }
            $newName = time() . "_" . $fileName;
            $uploadPath = "$folder/$newName";

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                $profile_image_path = "$folder/$newName";
            } else {
                $profile_image_path = "MyFiles/default.png";
            }
        }
    }

    $result = add_user($connection, $first_name, $last_name, $email, $password, $gender, $dob, $profile_image_path, $address, $is_active);

    // var_dump($result);
    if ($result) {
        header("location:../admin/add_user.php?msg=User Added Successfully!...");
    } else {
        header("location:../admin/add_user.php?msg=User Could not add, try again!...");
    }
} elseif (isset($_REQUEST['update_user'])) {

    extract($_REQUEST);

    $profile_image_path = $existing_image;
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $folder = 'MyFiles';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $newName = time() . '_' . $fileName;
            $uploadPath = "$folder/$newName";

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                $profile_image_path = $uploadPath;
            }
        }
    }

    $query = "UPDATE user SET first_name = '$first_name', last_name = '$last_name', email = '$email', password = '$password', gender = '$gender', date_of_birth = '$dob', user_image = '$profile_image_path', address = '$address', role_id = $role_type, updated_at = NOW() WHERE user_id = $user_id";

    $result = mysqli_query($connection, $query);

    if ($result) {
        header("location:../admin/users.php?msg=User updated successfully");
    } else {
        header("location:../admin/add_user.php?msg=Could not update user!..");
    }
}

?>