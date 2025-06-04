<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    exit;
}

$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post");
$row = mysqli_fetch_assoc($result);
$totalPosts = $row['total'];

$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post WHERE post_status = 'Active'");
$row = mysqli_fetch_assoc($result);
$totalActivePosts = $row['total'];

$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post WHERE post_status = 'InActive'");
$row = mysqli_fetch_assoc($result);
$totalInactivePosts = $row['total'];

$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post WHERE is_comment_allowed = 1");
$row = mysqli_fetch_assoc($result);
$totalCommentsEnabled = $row['total'];

$result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM post WHERE is_comment_allowed = 0");
$row = mysqli_fetch_assoc($result);
$totalCommentsDisabled = $row['total'];


admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <h2 class="heading-2 text-center mb-4">Posts Overview</h2>
    <div class="row g-4">

        <!-- All Posts -->
        <div class="col-md-4">
            <a href="posts.php?type=all" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa fa-file-alt fa-2x text-primary mb-3"></i>
                        <h6 class="card-title">All Posts</h6>
                        <p class="fw-bold fs-5"><?= $totalPosts ?></p>
                        <span class="btn btn-outline-primary btn-sm">View All</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Active Posts -->
        <div class="col-md-4">
            <a href="posts.php?type=active" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa fa-check-circle fa-2x text-success mb-3"></i>
                        <h6 class="card-title">Active Posts</h6>
                        <p class="fw-bold fs-5"><?= $totalActivePosts ?></p>
                        <span class="btn btn-outline-success btn-sm">View Active</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inactive Posts -->
        <div class="col-md-4">
            <a href="posts.php?type=inactive" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa fa-ban fa-2x text-danger mb-3"></i>
                        <h6 class="card-title">Inactive Posts</h6>
                        <p class="fw-bold fs-5"><?= $totalInactivePosts ?></p>
                        <span class="btn btn-outline-danger btn-sm">View Inactive</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="posts.php?type=comments_enabled" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa fa-comments fa-2x text-info mb-3"></i>
                        <h6 class="card-title">Comments Enabled</h6>
                        <p class="fw-bold fs-5"><?= $totalCommentsEnabled ?></p>
                        <span class="btn btn-outline-info btn-sm">View Enabled</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="posts.php?type=comments_disabled" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa fa-comment-slash fa-2x text-secondary mb-3"></i>
                        <h6 class="card-title">Comments Disabled</h6>
                        <p class="fw-bold fs-5"><?= $totalCommentsDisabled ?></p>
                        <span class="btn btn-outline-secondary btn-sm">View Disabled</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="posts.php?type=comments_disabled" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa  fa-2x text-secondary mb-3">+</i>
                        <h6 class="card-title">Add New Post</h6>
                        <p class="fw-bold fs-5 my-5"></p>
                        <a href="add_post.php"><span class="btn btn-outline-secondary btn-sm">Add Post</span></a>
                    </div>
                </div>
            </a>
        </div>

    </div>
</main>

<?php
admin_footer();
?>