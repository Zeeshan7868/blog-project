<?php  // add_blog.php

// echo "<pre>";
// print_r($_REQUEST);
// echo "</pre>";

session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
  header("location:../index.php");
  die();
}

admin_header();
admin_navbar();


$flag = false;


if (isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['blog_id'])) {
  $flag = true;
  $id = $_GET['blog_id'];
  $query = "SELECT * FROM blog WHERE blog_id = $id";
  $result = mysqli_query($connection, $query);
  if ($result->num_rows > 0) {
    $blog = mysqli_fetch_assoc($result);
  }
}
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="col-lg-8 mx-auto bg-white p-4 rounded shadow-sm">

    <?php if (isset($_GET['msg'])) { ?>
      <p id="msg" class="text-center text-success"><?= $_GET['msg'] ?></p>
    <?php } ?>

    <h3 class="mb-4 text-center fw-bold text-primary"><?= $flag ? 'Edit Blog' : 'Add New Blog' ?></h3>

    <form action="../processes/blog_process.php" method="POST" enctype="multipart/form-data">
      <?php if ($flag) { ?>
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
      <?php } else { ?>
        <input type="hidden" name="action" value="add">
      <?php } ?>

      <div class="mb-3">
        <label class="form-label">Blog Title</label>
        <input type="text" name="blog_title" value="<?= $blog['blog_title'] ?? "" ?>" class="form-control" placeholder="Enter blog Title" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Posts Per Page</label>
        <input type="number" name="posts_per_page" class="form-control" value="<?= $blog['post_per_page'] ?? "" ?>" placeholder="E.g. 10" min="6" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Background Image</label>
        <input type="file" name="blog_background_image" class="form-control" accept="image/*">
      </div>

      <?php if ($flag && !empty($blog['blog_background_image'])) { ?>
        <div class="mb-3">
          <label class="form-label">Current Image</label><br>
          <img src="../processes/<?= $blog['blog_background_image'] ?? "" ?>" alt="Current Background" style="max-width:100%; height:auto;">
        </div>
      <?php } ?>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="blog_status" class="form-select">
          <option value="Active" <?php
                                  if (isset($blog['blog_status']) and $blog['blog_status'] === 'Active') {
                                    echo 'selected';
                                  }
                                  ?>>Active</option>
          <option value="InActive" <?php
                                    if (isset($blog['blog_status']) and $blog['blog_status'] === 'InActive') {
                                      echo 'selected';
                                    }

                                    ?>>InActive</option>
        </select>
      </div>

      <div class="d-flex justify-content-center gap-2">
        <button type="submit" class="btn btn-primary"><?= $flag ? 'Update Blog' : 'Add Blog' ?></button>
        <a href="blogs.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</main>

<?php admin_footer(); ?>