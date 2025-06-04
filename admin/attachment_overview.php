<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    exit;
}



$resultAll = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post_atachment");
$rowAll = mysqli_fetch_assoc($resultAll);
$totalAllAttach = $rowAll['total'];


$resultActive = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post_atachment WHERE is_active = 'Active'");
$rowActive = mysqli_fetch_assoc($resultActive);
$totalActiveAttach = $rowActive['total'];


$resultInactive = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post_atachment WHERE is_active = 'InActive'");
$rowInactive = mysqli_fetch_assoc($resultInactive);
$totalInactiveAttach = $rowInactive['total'];


admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <h2 class="heading-2 text-center mb-4">Attachments Overview</h2>
  <div class="row g-4">

    
    <div class="col-md-4">
      <a href="attachments.php?type=all" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-paperclip fa-2x text-primary mb-3"></i>
            <h6 class="card-title">All Attachments</h6>
            <p class="fw-bold fs-5"><?= $totalAllAttach ?></p>
            <span class="btn btn-outline-primary btn-sm">View All</span>
          </div>
        </div>
      </a>
    </div>

    
    <div class="col-md-4">
      <a href="attachments.php?type=active" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-check-circle fa-2x text-success mb-3"></i>
            <h6 class="card-title">Active Attachments</h6>
            <p class="fw-bold fs-5"><?= $totalActiveAttach ?></p>
            <span class="btn btn-outline-success btn-sm">View Active</span>
          </div>
        </div>
      </a>
    </div>

    
    <div class="col-md-4">
      <a href="attachments.php?type=inactive" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-ban fa-2x text-danger mb-3"></i>
            <h6 class="card-title">Inactive Attachments</h6>
            <p class="fw-bold fs-5"><?= $totalInactiveAttach ?></p>
            <span class="btn btn-outline-danger btn-sm">View Inactive</span>
          </div>
        </div>
      </a>
    </div>

  </div>
</main>

<?php
admin_footer();
?>
