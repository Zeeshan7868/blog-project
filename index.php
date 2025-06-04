<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();

// if(!isset($_SESSION['user']['email'])){
//   header("location:login.php?msg=Please Login First!...");
// }

$latest_post_query  = "SELECT * FROM post where post_status = 'Active' order by created_at desc limit 0,5";
$latest_post_result = mysqli_query($connection, $latest_post_query);

$all_post_query  = "SELECT * FROM post where post_status = 'Active' limit 6";
$all_post_result = mysqli_query($connection, $all_post_query);



?>

<section class="hero-section text-white d-flex align-items-center">
  <div class="overlay"></div>
  <div class="container text-center">
    <h1 class="display-4 fw-bold">Unlock Knowledge with Our Latest Blogs</h1>
    <p class="lead mb-4">
      Explore expert insights, tips, and guides across a wide range of topics. Expand your knowledge today.
    </p>
    <form class="d-flex justify-content-center" action="search.php" method="GET">
      <input
        type="search"
        name="q"
        class="form-control form-control-lg w-50 me-2 rounded-pill"
        placeholder="Search"
        required>
      <button type="submit" class="btn btn-dark btn-lg rounded-pill px-4">Search</button>
    </form>

  </div>
</section>

<!-- POSTS LAYOUT -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT: All Posts (4 cards) -->
      <div class="col-lg-8">
        <h2 class="section-title mb-4">All Posts</h2>
        <div class="row g-4">

          <?php
          if ($all_post_result->num_rows > 0) {
            while ($all_post_row = mysqli_fetch_assoc($all_post_result)) {
          ?>

              <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                  <img src="<?= "processes/" . $all_post_row['featured_image'] ?>" style="width:100%; height:200px; object-fit:cover" class="card-img-top rounded-top" alt="Post Image">
                  <div class="card-body">
                    <h5 class="card-title"><?= $all_post_row['post_title'] ?></h5>
                    <p class="card-text text-muted small">
                      <?= substr($all_post_row['post_summary'], 0, 80)  ?>...
                    </p>
                  </div>
                  <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    <a href="view_post.php?post_id=<?= $all_post_row['post_id'] ?> " class="btn btn-outline-primary btn-sm">Read More</a>
                    <small class="text-muted"><?= date(" F Y h:i:s A", strtotime($all_post_row['created_at'])) ?></small>
                  </div>
                </div>
              </div>
          <?php
            }
          }
          ?>

        </div>
        
        <div class="text-center mt-4">
          <a href="all_posts.php" class="btn btn-primary btn">See More Posts</a>
        </div>
      </div>

      <!-- RIGHT: Latest 5 Posts -->

      <div class="col-lg-4">
        <h2 class="section-title mb-4">Latest Posts</h2>
        <ul class="list-group list-group-flush">
          <?php
          while ($latest_row = mysqli_fetch_assoc($latest_post_result)) {
          ?>

            <li class="list-group-item d-flex align-items-center py-3">
              <img src="<?= "processes/" . $latest_row['featured_image'] ?>" alt="" class="rounded me-3" style="width:64px;height:64px;object-fit:cover;">
              <div>
                <a href="view_post.php?post_id=<?= $latest_row['post_id'] ?>" class="fw-semibold text-dark"><?= $latest_row['post_title'] ?></a>
                <div class="text-muted small"><?= date("F Y h:i:s A", strtotime($latest_row['created_at'])) ?></div>
              </div>
            </li>
          <?php
          }
          ?>
        </ul>
      </div>

    </div>
  </div>
</section>



<?php
user_footer();
?>