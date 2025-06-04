
<?php

session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
// require("../functions/user_functions.php");

if ( isset($_SESSION['user']['email']) AND $_SESSION['user']['role_id'] == 2) {
        header("location:../index.php");
        die();
}

  // user_navbar();
  admin_header();
  admin_navbar();

?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="col-lg-6 mx-auto bg-white p-4 rounded shadow-sm">
    <h3 class="mb-4 text-center fw-bold text-primary">Add New Comment</h3>

    <form action="store_comment.php" method="POST">
      
      <div class="mb-3">
        <label class="form-label">Post ID</label>
        <select name="post_id" class="form-select" required>
          <option value="">-- Select Post --</option>
          <option value="12">Understanding REST APIs</option>
          <option value="13">PHP Basics for Beginners</option>
        </select>
      </div>

      
      <div class="mb-3">
        <label class="form-label">User ID</label>
        <select name="user_id" class="form-select" required>
          <option value="">-- Select User --</option>
          <option value="5">John Doe</option>
          <option value="6">Jane Smith</option>
        </select>
      </div>

      
      <div class="mb-3">
        <label for="comment_text" class="form-label">Comment</label>
        <textarea name="comment_text" id="comment_text" rows="4" class="form-control" placeholder="Enter comment" required></textarea>
      </div>

      
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" selected>Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      
      <div class="d-flex justify-content-center gap-2">
        <button type="submit" class="btn btn-primary">Create Comment</button>
        <a href="comments.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</main>


<?php
admin_footer();
?>