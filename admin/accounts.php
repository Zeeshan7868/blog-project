<?php

session_start();
require("../require/dashboard_layout.php");
// require("../require/layout.php");
require("../require/db_connection/connection.php");

if ( isset($_SESSION['user']['email']) AND $_SESSION['user']['role_id'] == 2) {
        header("location:../index.php");
        die();
}



$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE role_id = 2");
$row = mysqli_fetch_assoc($result);
$totalUsers = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE role_id = 1");
$row = mysqli_fetch_assoc($result);
$totalAdmins = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE is_approved = 'Pending'");
$row = mysqli_fetch_assoc($result);
$totalPending = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE is_approved = 'Approved'");
$row = mysqli_fetch_assoc($result);
$totalApproved = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE is_active = 'Active'");
$row = mysqli_fetch_assoc($result);
$totalActive = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE is_active = 'InActive'");
$row = mysqli_fetch_assoc($result);
$totalInactive = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user WHERE is_approved = 'Rejected'");
$row = mysqli_fetch_assoc($result);
$totalRejected = $row['total'];


admin_header();
admin_navbar();


?>


<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <h2 class="heading-2 text-center mb-4">Accounts Overview</h2>
  <div class="row g-4">

  <!-- All Users -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=all" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-users fa-2x text-primary mb-3"></i>
          <h6 class="card-title">All Users</h6>
          <p class="fw-bold fs-5"><?= $totalUsers ?></p>
          <span class="btn btn-outline-primary btn-sm">View All</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Admins -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=admin" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-user-shield fa-2x text-success mb-3"></i>
          <h6 class="card-title">Admins</h6>
          <p class="fw-bold fs-5"><?= $totalAdmins ?></p>
          <span class="btn btn-outline-success btn-sm">View Admins</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Pending -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=pending" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-user-clock fa-2x text-warning mb-3"></i>
          <h6 class="card-title">Pending Accounts</h6>
          <p class="fw-bold fs-5"><?= $totalPending ?></p>
          <span class="btn btn-outline-warning btn-sm">Pending Users</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Approved -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=approved" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-user-check fa-2x text-info mb-3"></i>
          <h6 class="card-title">Approved Accounts</h6>
          <p class="fw-bold fs-5"><?= $totalPending ?></p>
          <span class="btn btn-outline-info btn-sm">View Approved</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Active -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=active" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-user-circle fa-2x text-secondary mb-3"></i>
          <h6 class="card-title">Active Accounts</h6>
          <p class="fw-bold fs-5"><?= $totalApproved ?></p>
          <span class="btn btn-outline-secondary btn-sm">View Active</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Inactive -->
  <div class="col-md-4 col-sm-6">
    <a href="users.php?type=inactive" class="text-decoration-none text-dark">
      <div class="card text-center shadow border-0">
        <div class="card-body">
          <i class="fa-solid fa-user-slash fa-2x text-danger mb-3"></i>
          <h6 class="card-title">Inactive Accounts</h6>
          <p class="fw-bold fs-5"><?= $totalInactive ?></p>
          <span class="btn btn-outline-danger btn-sm">View Inactive</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Rejected -->
<div class="col-md-4 col-sm-6">
  <a href="users.php?type=rejected" class="text-decoration-none text-dark">
    <div class="card text-center shadow border-0">
      <div class="card-body">
        <i class="fa-solid fa-user-xmark fa-2x text-dark mb-3"></i>
        <h6 class="card-title">Rejected Accounts</h6>
        <p class="fw-bold fs-5"><?= $totalRejected ?></p>
        <span class="btn btn-outline-dark btn-sm">View Rejected</span>
      </div>
    </div>
  </a>
</div>


</div>

</main>



<?php
admin_footer();
?>