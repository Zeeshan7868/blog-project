<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();



$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$is_category_set = false;
if (isset($_GET['category_id']) && isset($_GET['category_title'])) {
    $is_category_set = true;
    $category_id = $_GET['category_id'];
    $category_title = $_GET['category_title'];
}



if ($is_category_set) {

    $post_query = "SELECT * FROM post p
                    JOIN post_category pc ON pc.post_id = p.post_id
                    JOIN category c ON pc.category_id = c.category_id
                    WHERE c.category_id = $category_id 
                    AND p.post_status = 'Active'
                    ORDER BY p.created_at DESC
                    LIMIT $start, $limit ";
} else {

    $post_query = "SELECT * FROM post 
                    WHERE post_status = 'Active' 
                    ORDER BY created_at DESC 
                    LIMIT $start, $limit";
}
$post_result = mysqli_query($connection, $post_query) or mysqli_error($connection);

//   var_dump($post_result);
//     die();

if ($is_category_set) {

    $total_query = "SELECT COUNT(*) AS total FROM post p
                    JOIN post_category pc
                    ON pc.`post_id` = p.`post_id`
                    JOIN category c
                    ON pc.`category_id` = c.`category_id`
                    where p.post_status = 'Active'  AND c.category_id = " . $_GET['category_id'];
} else {

    $total_query = "SELECT COUNT(*) AS total FROM post where post_status = 'Active'";
}
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $limit);
?>

<section class="py-5 " style="min-height: 80vh;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12">
 
                <h2 class="fw-bold mb-4 text-center">
                    <?php
                    if ($is_category_set) {
                        echo $_GET['category_title'] . " Posts";
                    } else {
                        echo "All Posts";
                    }
                    ?></h2>

                <div class="row g-4">
                    <?php if (mysqli_num_rows($post_result) > 0) { ?>
                        <?php while ($post = mysqli_fetch_assoc($post_result)) { ?>
                            <div class="col-md-3">
                                <div class="card h-100 shadow-sm">

                                    <?php
                                    $imagePath = "processes/" . $post['featured_image'];
                                    $flag = false;

                                    if (!empty($post['featured_image'])) {
                                        if (file_exists($imagePath)) {
                                            $flag = true;
                                        }
                                    }

                                    if ($flag == true) {
                                        echo '<img src="' . $imagePath . '" class="card-img-top" alt="Post Image" style="height: 200px; object-fit: cover;">';
                                    }
                                    ?>


                                    <div class="card-body">
                                        <h5 class="card-title"><?= $post['post_title'] ?></h5>
                                        <p class="card-text">
                                            <?= substr($post['post_summary'], 0, 80) ?>...
                                        </p>
                                        <a href="view_post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-primary btn-sm">Read More</a>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        Published on <?= date('F j, Y', strtotime($post['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    <?php } else { ?>
                        <p>No posts available.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        
        <?php if ($total_pages > 1) { ?>
            <div class="pagination-container text-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <?php
                        if ($page > 1) {
                            if ($is_category_set) {
                        ?>

                                <li class="page-item"><a class="page-link" href="?category_id=<?= $_GET['category_id'] ?>&category_title=<?= $_GET['category_title'] ?>&page=<?= $page - 1 ?>">Previous</a></li>
                            <?php
                            } else {
                            ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($is_category_set) {
                        ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&category_id=<?= $_GET['category_id'] ?>&category_title=<?= $_GET['category_title'] ?>"><?= $i ?></a>
                                </li>
                            <?php
                            } else {
                            ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                        <?php
                        if ($page < $total_pages) {
                            if ($is_category_set) {
                        ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&category_id=<?= $_GET['category_id'] ?>&category_title=<?= $_GET['category_title'] ?>">Next</a></li>
                            <?php
                            } else {
                            ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>

                        <?php
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        <?php } ?>
    </div>
</section>

<?php
user_footer();
?>