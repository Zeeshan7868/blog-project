
<?php

session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
require("../require/db_connection/connection.php");

// Total Blogs
$blogs_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM blog");
$blogs_count_row = mysqli_fetch_assoc($blogs_count_result);
$blogs_count = $blogs_count_row['total'];

// Total Posts
$posts_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post");
$posts_count_row = mysqli_fetch_assoc($posts_count_result);
$posts_count = $posts_count_row['total'];

// Attachments
$attachments_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post_atachment");
$attachments_count_row = mysqli_fetch_assoc($attachments_count_result);
$attachments_count = $attachments_count_row['total'];

// Accounts
$accounts_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user");
$accounts_count_row = mysqli_fetch_assoc($accounts_count_result);
$accounts_count = $accounts_count_row['total'];

// Feedbacks
$feedbacks_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM user_feedback");
$feedbacks_count_row = mysqli_fetch_assoc($feedbacks_count_result);
$feedbacks_count = $feedbacks_count_row['total'];

// Categories
$categories_count_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM category");
$categories_count_row = mysqli_fetch_assoc($categories_count_result);
$categories_count = $categories_count_row['total'];



if(!isset($_SESSION['user']['email'])){
        header("location:../login.php?Please Login First!...");
        die();
}
else if (isset($_SESSION['user']['email']) AND $_SESSION['user']['role_id'] == 2) {
        header("location:../index.php");
        die();
}
  // user_navbar();
  admin_header();
  admin_navbar();

?>


    <main class="col-12 col-md-9 dashboard p-4">
      <div class="mt-4">
        <h2 class="heading-2 text-center">Dashboard Overview</h2>
      </div>

      <div class="row g-4">
        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-layer-group fa-2x text-primary mb-3"></i>
              <h6 class="card-title">Total Blogs</h6>
              <p class="fw-bold fs-5"><?= $blogs_count ?></p>
              <a href="blogs.php" class="btn btn-outline-primary btn-sm">View Blogs</a>
            </div>
          </div>
        </div>

        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-pen-to-square fa-2x text-success mb-3"></i>
              <h6 class="card-title">Total Posts</h6>
              <p class="fw-bold fs-5"><?= $posts_count ?></p>
              <a href="posts_overview.php" class="btn btn-outline-success btn-sm">View Posts</a>
            </div>
          </div>
        </div>

        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-paperclip fa-2x text-dark mb-3"></i>
              <h6 class="card-title">Attachments</h6>
              <p class="fw-bold fs-5"><?= $attachments_count ?></p>
              <a href="attachment_overview.php" class="btn btn-outline-dark btn-sm">Manage</a>
            </div>
          </div>
        </div>


        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-user fa-2x text-info mb-3"></i>
              <h6 class="card-title">Accounts</h6>
              <p class="fw-bold fs-5"><?= $accounts_count ?></p>
              <a href="accounts.php" class="btn btn-outline-info btn-sm">View Users</a>
            </div>
          </div>
        </div>

        
      

        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-envelope-open-text fa-2x text-danger mb-3"></i>
              <h6 class="card-title">Feedbacks</h6>
              <p class="fw-bold fs-5"><?= $feedbacks_count ?></p>
              <a href="feedbacks.php" class="btn btn-outline-danger btn-sm">Check</a>
            </div>
          </div>
        </div>

        
        <div class="col-md-4 col-sm-6">
          <div class="card text-center shadow border-0">
            <div class="card-body">
              <i class="fa-solid fa-tags fa-2x text-secondary mb-3"></i>
              <h6 class="card-title">Categories</h6>
              <p class="fw-bold fs-5"><?= $categories_count ?></p>
              <a href="category_overview.php" class="btn btn-outline-secondary btn-sm">Categories</a>
            </div>
          </div>
        </div>
      </div>
    </main>


<?php
  admin_footer();
?>