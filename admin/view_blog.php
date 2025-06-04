<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
require("../require/db_connection/connection.php");

if (!isset($_SESSION['user']['email'])) {
    header("location:../login.php?Please Login First!...");
    die();
} else if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}

admin_header();
admin_navbar();


if (!isset($_GET['blog_id'])) {
    echo "<h5 class='text-center text-danger mt-5'>Invalid Blog ID</h5>";
    exit;
}
$blog_id = $_GET['blog_id'];


$blog_query = "SELECT b.*, COUNT(p.post_id) AS post_count
               FROM blog b
               LEFT JOIN post p ON p.blog_id = b.blog_id
               WHERE b.blog_id = $blog_id
               GROUP BY b.blog_id";
$blog_result = mysqli_query($connection, $blog_query);

if (!$blog_result || mysqli_num_rows($blog_result) === 0) {
    echo "<h5 class='text-center text-danger mt-5'>Blog not found</h5>";
    exit;
}
$blog = mysqli_fetch_assoc($blog_result);


$limit = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$post_query = "SELECT * FROM post WHERE blog_id = $blog_id ORDER BY post_id DESC LIMIT $offset, $limit";
$post_result = mysqli_query($connection, $post_query);


$total_post_query = "SELECT COUNT(*) as total FROM post WHERE blog_id = $blog_id";
$total_post_result = mysqli_query($connection, $total_post_query);
$total_posts_row = mysqli_fetch_assoc($total_post_result);
$total_posts = $total_posts_row['total'];
$total_pages = ceil($total_posts / $limit);
?>

<section class="py-5 col-9">
    <div class="container" style="max-width: 1000px;">
        
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <img src="<?php echo '../processes/' . $blog['blog_background_image']; ?>"  alt="Profile Image"
                        class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h4 class="mb-0"><?php echo $blog['blog_title']; ?></h4>
                        <small class="text-muted">1.2K followers • Total Posts: <?php echo $blog['post_count']; ?></small>
                    </div>
                </div>
                
            </div>
        </div>

        
        <div class="mb-4">
            <h5 class="mb-3">Posts</h5>
            <div class="row g-4">
                <?php
                if ($post_result->num_rows > 0) {
                    while ($post = mysqli_fetch_assoc($post_result)) {
                ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo '../processes/' . $post['featured_image']; ?>" style="height:200px; width:100%; object-fit:cover;" class="card-img-top" alt="Post Image">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo $post['post_title']; ?></h6>
                                    <p class="text-muted mb-2"><small><?php echo date('F j, Y', strtotime($post['created_at'])); ?></small></p>
                                    <p class="card-text small"><?php echo substr($post['post_description'], 0, 70) . '...'; ?></p>
                                    <a href="view_post.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center text-muted'>No posts found for this blog.</p>";
                }
                ?>
            </div>
        </div>

        
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Blog pagination">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?blog_id=<?php echo $blog_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='?blog_id=$blog_id&page=$i'>$i</a></li>";
                }
                ?>
                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?blog_id=<?php echo $blog_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>

<?php admin_footer(); ?>
