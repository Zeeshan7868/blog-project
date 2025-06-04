<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    exit;
}


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM category");
$row = mysqli_fetch_assoc($result);
$totalAllCats = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM category WHERE category_status = 'Active'");
$row = mysqli_fetch_assoc($result);
$totalActiveCats = $row['total'];


$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM category WHERE category_status = 'InActive'");
$row = mysqli_fetch_assoc($result);
$totalInactiveCats = $row['total'];

admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <h2 class="heading-2 text-center mb-4">Categories Overview</h2>
  <div class="row g-4">

    <div class="col-md-4">
      <a href="categories.php?type=all" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-tags fa-2x text-primary mb-3"></i>
            <h6 class="card-title">All Categories</h6>
            <p class="fw-bold fs-5"><?= $totalAllCats ?></p>
            <span class="btn btn-outline-primary btn-sm">View All</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="categories.php?type=active" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-check-circle fa-2x text-success mb-3"></i>
            <h6 class="card-title">Active Categories</h6>
            <p class="fw-bold fs-5"><?= $totalActiveCats ?></p>
            <span class="btn btn-outline-success btn-sm">View Active</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="categories.php?type=inactive" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fa fa-ban fa-2x text-danger mb-3"></i>
            <h6 class="card-title">Inactive Categories</h6>
            <p class="fw-bold fs-5"><?= $totalInactiveCats ?></p>
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
