<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");

user_header();
user_navbar();

$search_query = "";
$posts = [];

if (isset($_GET['q'])) {
    $search_query = trim($_GET['q']);
    $search_query_sql = mysqli_real_escape_string($connection, $search_query);

    $sql = "SELECT * FROM post WHERE post_status = 'Active' AND (post_title LIKE '%$search_query_sql%' OR post_summary LIKE '%$search_query_sql%') ORDER BY created_at DESC";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }
    }
}
?>
<form action="search.php" method="GET" class="d-flex justify-content-center mb-4 mt-5">
  <input
    type="search"
    name="q"
    value="<?= htmlspecialchars($search_query ?? '') ?>"
    class="form-control w-25 me-2 rounded-pill"
    placeholder="Search"
    required
  >
  <button type="submit" class="btn btn-dark rounded-pill px-4">Search</button>
</form>


<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4">Search Results for: <em><?= htmlspecialchars($search_query) ?></em></h2>

    <?php if (count($posts) > 0): ?>
      <div class="row g-4">
        <?php foreach ($posts as $post): ?>
          <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
              <img src="<?= "processes/" . $post['featured_image'] ?>" style="width:100%; height:200px; object-fit:cover" class="card-img-top rounded-top" alt="Post Image">
              <div class="card-body">
                <h5 class="card-title"><?= $post['post_title'] ?></h5>
                <p class="card-text text-muted small">
                  <?= substr($post['post_summary'], 0, 80) ?>...
                </p>
              </div>
              <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                <a href="view_post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-outline-primary btn-sm">Read More</a>
                <small class="text-muted"><?= date("F Y h:i:s A", strtotime($post['created_at'])) ?></small>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No posts found matching your search.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
</section>

<?php
user_footer();
?>
