<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");
require("../functions/user_functions.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
  header("location:../index.php");
  die();
}

$flag = false;
if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'edit') {
  $flag   = true;
  $result = select_all_from_user_id($connection, $_REQUEST['user_id']);
  $row    = mysqli_fetch_assoc($result);
}

admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <p class="text-center text-success"><?= $_GET['msg'] ?? '' ?></p>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5 bg-white p-4 rounded shadow-sm">
      <h3 class="mb-4 text-center fw-bold text-primary">
        <?= $flag ? 'Edit Account' : 'Add New Account' ?>
      </h3>

      <form action="../processes/user_process.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
        <input type="hidden" name="existing_image" value="<?= $row['user_image'] ?>">

        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">First Name</label>
            <input
              type="text"
              name="first_name"
              class="form-control"
              placeholder="Enter first name"
              value="<?= $row['first_name'] ?? '' ?>">
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Last Name</label>
            <input
              type="text"
              name="last_name"
              class="form-control"
              placeholder="Enter last name"
              value="<?= $row['last_name'] ?? '' ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input
            type="email"
            name="email"
            class="form-control"
            placeholder="Enter email"
            <?= $flag == true? "disabled": "" ?>
            value="<?= $row['email'] ?? '' ?>">
        </div>
        <?php
        if(!$flag){
        ?>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input
            type="<?= $flag ? 'text' : 'password' ?>"
            name="password"
            class="form-control"
            value="<?= $row['password'] ?? '' ?>">
        </div>
        <?php
          }
        ?>

        <div class="mb-3">
          <label class="form-label d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" <?php if (isset($row['gender']) && $row['gender'] === 'Male') { echo 'checked'; } ?>>
            <label class="form-check-label" for="genderMale">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" <?php if (isset($row['gender']) && $row['gender'] === 'Female') { echo 'checked'; } ?>>
            <label class="form-check-label" for="genderFemale">Female</label>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Date of Birth</label>
          <input
            type="date"
            name="dob"
            class="form-control"
            value="<?= $row['date_of_birth'] ?? '' ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Profile Image</label>
          <input
            type="file"
            name="profile_image"
            class="form-control">
        </div>

        <?php if ($flag && !empty($row['user_image'])) { ?>
          <div class="mb-3">
            <label class="form-label">Current Image</label>
            <div>
              <img
                src="../processes/<?= $row['user_image'] ?>"
                alt="Current Profile Image"
                class="img-fluid rounded"
                style="max-height: 200px;">
            </div>
          </div>
        <?php } ?>

        <div class="mb-3">
          <label class="form-label">Address</label>
          <textarea
            name="address"
            rows="3"
            class="form-control"
            placeholder="Enter address"><?= $row['address'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Role Type</label>
          <select name="role_type" class="form-select">
            <option value="" disabled <?php if (!$flag) { echo 'selected'; } ?>>Choose role</option>
            <option value="1" <?php if (isset($row['role_id']) && $row['role_id'] == 1) { echo 'selected'; } ?>>Admin</option>
            <option value="2" <?php if (isset($row['role_id']) && $row['role_id'] == 2) { echo 'selected'; } ?>>User</option>
          </select>
        </div>

        <?php if (!$flag) { ?>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select">
              <option value="Active" selected>Active</option>
              <option value="InActive">InActive</option>
            </select>
          </div>
        <?php } ?>

        <div class="d-flex justify-content-center gap-2">
          <button
            type="submit"
            name="<?= $flag ? 'update_user' : 'add_user' ?>"
            value="<?= $flag ? 'update_user' : 'add_user' ?>"
            class="btn btn-primary">
            <?= $flag ? 'Update User' : 'Add User' ?>
          </button>
          <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php admin_footer(); ?>
