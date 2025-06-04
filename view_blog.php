<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();

// if (!isset($_SESSION['user']['email'])) {
//     header("location:login.php?msg=Please Login First!...");
// }

$blog_id = $_GET['blog_id'];


$blog_query = "SELECT * FROM blog WHERE blog_id = $blog_id";
$blog_result = mysqli_query($connection, $blog_query);
$blog_row = mysqli_fetch_assoc($blog_result);

// echo "<pre>";
// print_r($blog_row);


$count_query = "SELECT COUNT(*) AS post_count FROM post WHERE post_status = 'Active' AND blog_id = $blog_id";
$count_result = mysqli_query($connection, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$post_count = $count_row['post_count'];


$limit = $blog_row['post_per_page'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;


$post_query = "SELECT * FROM post WHERE post_status = 'Active' AND blog_id = $blog_id ORDER BY post_id DESC LIMIT $offset, $limit";
$post_result = mysqli_query($connection, $post_query);

$total_query = "SELECT COUNT(*) AS total FROM post WHERE post_status = 'Active' AND blog_id = $blog_id";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $limit);

?>
<section class="py-5">
    <div class="container" style="max-width: 1000px;">

        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <img src="processes/<?= $blog_row['blog_background_image'] ?>" class="rounded-circle me-3" alt="Profile Image" style="width: 60px; height: 60px;">
                    <div>
                        <h4 class="mb-0"><?= $blog_row['blog_title'] ?></h4>
                        <small class="text-muted">10.4K followers • Active Posts: <?= $post_count ?></small>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm">Follow</button>
            </div>
        </div>


        <div class="mb-4">
            <h5 class="mb-3">Posts</h5>

            <?php if ($post_result->num_rows > 0) { ?>
                <div class="row g-4">
                    <?php while ($post = mysqli_fetch_assoc($post_result)) { ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <img src="processes/<?= $post['featured_image'] ?>" style="height: 200px; width:100%; object-fit:cover" class="card-img-top" alt="Post Image">
                                <div class="card-body">
                                    <h6 class="card-title"><?= $post['post_title'] ?></h6>
                                    <p class="text-muted mb-2"><small><?= date('F j, Y', strtotime($post['created_at'])) ?></small></p>
                                    <p class="card-text small"><?= substr($post['post_description'], 0, 80) ?>...</p>
                                    <a href="view_post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="text-center text-muted py-5">
                    <h6>No posts added yet.</h6>
                </div>
            <?php } ?>
        </div>




        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?blog_id=<?= $blog_id ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?blog_id=<?= $blog_id ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?blog_id=<?= $blog_id ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>



    </div>
</section>

<?php
user_footer();
?>