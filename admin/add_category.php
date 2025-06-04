<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");
if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) { header("location:../index.php"); die(); }

$editing = false;
if (isset($_GET['action']) && $_GET['action']==='edit') {
  $editing = true;
  $id = $_GET['category_id'];
  $row = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM category WHERE category_id=$id"));
}

admin_header(); 
admin_navbar();
?>
<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="col-lg-6 mx-auto bg-white p-4 rounded shadow-sm">
    <h3 class="mb-4 text-center fw-bold text-primary"><?= $editing? 'Edit Category':'Add New Category' ?></h3>
    <form action="../processes/category_process.php" method="POST">
      <input type="hidden" name="action" value="<?= $editing? 'edit':'add' ?>">
      <?php if($editing): ?>
        <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
      <?php endif; ?>
      <div class="mb-3">
        <label class="form-label">Category Title</label>
        <input type="text" name="title" class="form-control" value="<?= $row['category_title'] ?? '' ?>" placeholder="Enter category title" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control" placeholder="Enter category description" required><?= $row['category_description'] ?? '' ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Active" <?= (!empty($row['category_status']) && $row['category_status']==='Active')?'selected':'' ?>>Active</option>
          <option value="Inactive" <?= (!empty($row['category_status']) && $row['category_status']==='Inactive')?'selected':'' ?>>Inactive</option>
        </select>
      </div>
      <div class="d-flex justify-content-center gap-2">
        <button type="submit" class="btn btn-primary"><?= $editing? 'Update':'Create' ?> Category</button>
        <a href="categories.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</main>
<?php admin_footer(); ?>