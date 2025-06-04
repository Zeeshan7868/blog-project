<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();
// if (!isset($_SESSION['user']['email'])) {
//     header("location:login.php?msg=Please Login First!...");
// }

$query = "SELECT * From category";
$result = mysqli_query($connection, $query);

// user_header();
// user_navbar();
?>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold mb-5 text-center text-uppercase text-primary">Browse Categories</h2>
        <div class="row g-4">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 hover-shadow">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-dark fw-bold">
                                <a href="all_posts.php?category_id=<?= $row['category_id'] ?>&category_title=<?= $row['category_title'] ?>" class="text-decoration-none text-primary">
                                    <?= $row['category_title'] ?>
                                </a>
                            </h5>
                            <p class="card-text text-secondary"><?= $row['category_description'] ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="all_posts.php?category_id=<?= $row['category_id'] ?>&category_title=<?= $row['category_title'] ?>" class="btn btn-outline-primary btn-sm w-100">View Posts</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>


<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>

<?php user_footer(); ?>