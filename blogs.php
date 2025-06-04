<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();

// if (!isset($_SESSION['user']['email'])) {
//     header("location:login.php?msg=Please Login First!...");

// }

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$is_search = false;
if ($search !== "") {
    $is_search = true;
}
?>

<h4 class="text-primary mt-3 text-center">Blogs</h4>
<section class="py-2" style="background-color: #f9f9f9;">
    <div class="container">
        <form method="GET" action="" class="mb-4 d-flex justify-content-center">
            <input type="text" name="search"
                value="<?php echo $search; ?>"
                placeholder="Search blogs by title..."
                class="form-control"
                style="max-width: 400px;">
            <button type="submit" class="btn btn-primary ms-2">Search</button>
        </form>

        <div class="d-flex flex-column align-items-center">
            <div class="col-8 mx-auto mb-5">
                <?php
                if ($is_search) {
                    $query = "SELECT b.*, COUNT(p.post_id) AS post_count 
                              FROM blog b 
                              LEFT JOIN post p ON p.blog_id = b.blog_id 
                              WHERE b.blog_title LIKE '%$search%'
                              GROUP BY b.blog_id";
                } else {
                    $limit = 10;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }

                    $offset = ($page - 1) * $limit;

                    $count_query = "SELECT COUNT(*) AS total FROM blog";
                    $count_result = mysqli_query($connection, $count_query);
                    $count_row = mysqli_fetch_assoc($count_result);
                    $total_records = $count_row['total'];
                    $total_pages = ceil($total_records / $limit);

                    $query = "SELECT b.*, COUNT(p.post_id) AS post_count 
                              FROM blog b 
                              LEFT JOIN post p ON p.blog_id = b.blog_id 
                              GROUP BY b.blog_id 
                              LIMIT $offset, $limit";
                }

                $result = mysqli_query($connection, $query);

                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="blog-item d-flex my-2 align-items-center justify-content-start" style="background-color: #ffffff; padding: 5px; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <img src="<?php echo 'processes/' . $row['blog_background_image']; ?>" class="img-fluid rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Blog Image">
                            <div>
                                <a href="view_blog.php?blog_id=<?php echo $row['blog_id']; ?>" class="fw-semibold text-decoration-none text-dark">
                                    <h5><?php echo $row['blog_title']; ?></h5>
                                </a>
                                <small>
                                    <?php echo $row['post_count']; ?>
                                    <?php
                                    if ($row['post_count'] <= 1) {
                                    ?>
                                        Post
                                    <?php
                                    } else {
                                    ?>
                                        Posts
                                    <?php
                                    }
                                    ?>
                                </small><br>
                                <small>Followers: 1.2K</small>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <p class="text-center text-muted">No blogs found.</p>
                <?php
                }
                ?>
            </div>
        </div>

        <?php
        if (!$is_search) {
           
        ?>
                <div class="pagination-container text-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-4">
                            <?php
                            if ($page <= 1) {
                            ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php
                            } else {
                                $prev = $page - 1;
                            ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $prev; ?>">Previous</a></li>
                                <?php
                            }

                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $page) {
                                ?>
                                    <li class="page-item active"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php
                                } else {
                                ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php
                                }
                            }

                            if ($page >= $total_pages) {
                                ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            <?php
                            } else {
                                $next = $page + 1;
                            ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $next; ?>">Next</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
        <?php
            
        }
        ?>
    </div>
</section>

<?php user_footer(); ?>