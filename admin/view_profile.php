<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");
require("../functions/user_functions.php");

if (!isset($_SESSION['user']['email']) || $_SESSION['user']['role_id'] != 1) {
  header("location:../login.php");
  die();
}

$user_id = $_SESSION['user']['user_id'];
$result = select_all_from_user_id($connection, $user_id);
if ($result->num_rows == 0) {
  header("location:users.php?msg=User not found");
  die();
}
$row = mysqli_fetch_assoc($result);

admin_header();
admin_navbar();
?>
<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="heading-2">View User</h2>
    <div>
      <?php if ($row['is_approved'] === 'Pending') { ?>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block">
          <input type="hidden" name="action" value="approval">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <button type="submit" name="approval_value" value="approve" class="btn btn-success btn-sm">Approve</button>
        </form>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block">
          <input type="hidden" name="action" value="approval">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <button type="submit" name="approval_value" value="reject" class="btn btn-danger btn-sm">Reject</button>
        </form>
      <?php } elseif ($row['is_approved'] === 'Approved') { ?>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block">
          <input type="hidden" name="action" value="approval">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <button type="submit" name="approval_value" value="reject" class="btn btn-danger btn-sm">Reject</button>
        </form>
      <?php } else { ?>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block">
          <input type="hidden" name="action" value="approval">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <button type="submit" name="approval_value" value="approve" class="btn btn-success btn-sm">Approve</button>
        </form>
      <?php } ?>
      <?php if ($row['is_active'] === 'Active') { ?>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block;">
          <input type="hidden" name="action" value="is_active">
          <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
          <input type="hidden" name="activeValue" value="InActive">
          <button type="submit" class="btn btn-warning btn-sm text-dark">Inactivate</button>
        </form>
      <?php } else { ?>
        <form action="../processes/general_process.php" method="POST" style="display:inline-block;">
          <input type="hidden" name="action" value="is_active">
          <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
          <input type="hidden" name="activeValue" value="Active">
          <button type="submit" class="btn btn-success btn-sm">Activate</button>
        </form>
      <?php } ?>


      <a href="add_user.php?action=edit&user_id=<?= $user_id ?>" class="btn btn-primary btn-sm">Edit</a>
      <a href="users.php" class="btn btn-secondary btn-sm">&laquo; Back</a>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4 text-center">
          <?php if ($row['user_image']) { ?>
            <img src="../processes/<?= $row['user_image'] ?>" class="img-fluid rounded" style="max-height:200px;" alt="Profile Image">
          <?php } else { ?>
            <div class="bg-secondary text-white d-inline-block rounded-circle" style="width:200px;height:200px;line-height:200px;font-size:2rem;">N/A</div>
          <?php } ?>
        </div>
        <div class="col-md-8">
          <table class="table table-borderless">
            <tr>
              <th>First Name:</th>
              <td><?= $row['first_name'] ?></td>
            </tr>
            <tr>
              <th>Last Name:</th>
              <td><?= $row['last_name'] ?></td>
            </tr>
            <tr>
              <th>Email:</th>
              <td><?= $row['email'] ?></td>
            </tr>
            <tr>
              <th>Gender:</th>
              <td><?= $row['gender'] ?></td>
            </tr>
            <tr>
              <th>DOB:</th>
              <td><?= $row['date_of_birth'] ?></td>
            </tr>
            <tr>
              <th>Address:</th>
              <td><?= $row['address'] ?></td>
            </tr>
            <tr>
              <th>Role:</th>
              <td><?= $row['role_id']==1? "Admin":"User" ?></td>
            </tr>
            <tr>
              <th>Approved:</th>
              <td><?= $row['is_approved'] ?></td>
            </tr>
            <tr>
              <th>Active:</th>
              <td><?= $row['is_active'] ?></td>
            </tr>
            <tr>
              <th>Created At:</th>
              <td><?= $row['created_at'] ?></td>
            </tr>
            <tr>
              <th>Updated At:</th>
              <td><?= $row['updated_at'] ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<?php admin_footer(); ?>