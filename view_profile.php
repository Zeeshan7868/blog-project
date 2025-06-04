<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    die();
}

require("require/layout.php");
require("require/db_connection/connection.php");

user_header();
user_navbar();

$user_id = $_SESSION['user']['user_id'];


$query = "SELECT * FROM user WHERE user_id = $user_id";
$result = mysqli_query($connection, $query);

if ($result->num_rows === 0) {
    echo "<div class='container mt-5'>User data not found.</div>";
    user_footer();
    die();
}

$user = mysqli_fetch_assoc($result);
?>

<style>

  .profile-container {
    min-height: 75vh; 
    display: flex;
    justify-content: center; 
    align-items: center; 
  }
  
 
</style>

<div class="container profile-container">
    <div class="card p-4" style="max-width:600px; width: 100%;">

        <div class="text-center mb-4">
            <img src="<?= 'processes/'. $user['user_image'] ?>" alt="Profile Image" class="rounded-circle" style="width:120px; height:120px; object-fit:cover;">
        </div>

        <table class="table table-bordered mb-4">
            <tr>
                <th>First Name</th>
                <td><?= $user['first_name'] ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?= $user['last_name'] ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= $user['email'] ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?= $user['gender'] ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?= $user['date_of_birth'] ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= $user['address'] ?></td>
            </tr>
        </table>

        
    </div>
</div>

<?php
user_footer();
?>
