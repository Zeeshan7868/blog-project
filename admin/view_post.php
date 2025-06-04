<?php

session_start();
require("../require/dashboard_layout.php");
// require("../require/layout.php");
// require("../functions/user_functions.php");
require("../require/db_connection/connection.php");

if (!isset($_SESSION['user']['email'])) {
    header("location:../login.php?Please Login First!...");
    die();
} else if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}

if (isset($_GET['post_id'])) {

    $post_id = $_GET['post_id'];

    $query = "SELECT * FROM post p
                JOIN blog b
                ON b.`blog_id` = p.`blog_id`
                JOIN USER u
                ON b.`user_id` = u.`user_id`
                WHERE post_id = " . $post_id;
    $result = mysqli_query($connection, $query);
    // var_dump($result);
    // die();
    $row = mysqli_fetch_assoc($result);
}


//   user_navbar();
admin_header();
admin_navbar();

?>

<section class="py-5 ms-5 col-8">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-white">
                        <h1 class="fw-bold mb-2"><?= $row['post_title'] ?></h1>
                        <p class="text-muted mb-0">Added by <strong><?= $row['first_name'] ?></strong> on <?= $row['created_at'] ?></p>
                        <p class="text-muted mb-0"> <?= "Blog: <b>" . $row['blog_title'] . "</b>" ?? ""  ?></p>
                    </div>
                    <div class="card-body">

                        <img src="<?= "../processes/" . $row['featured_image'] ?>" alt="Post Image" style="width: 100%; height:400px;object-fit:cover" class="img-fluid rounded mb-4">

                        <h5 class="fw-semibold">Summary</h5>
                        <blockquote class="border-start border-4 border-primary ps-3 fst-italic text-muted">
                            <?= $row['post_summary'] ?>
                        </blockquote>

                        <h5 class="fw-semibold mt-4">Description</h5>
                        <div class="post-content" style="overflow-wrap:anywhere; line-height:1.6;">
                            <?= $row['post_description'] ?>
                        </div>
                        <?php
                        $cat_query = "SELECT c.category_title 
                                    FROM post_category pc
                                    JOIN category c ON pc.category_id = c.category_id
                                    WHERE pc.post_id = " . (int)$post_id;
                        $cat_result = mysqli_query($connection, $cat_query);

                        if ($cat_result->num_rows > 0) {
                        ?>
                            <h5 class="fw-semibold mt-4">Categories</h5>
                            <div class="mb-3">
                                <?php while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                                    $category = $cat_row['category_title'];
                                ?>
                                    <span class="badge bg-primary me-2"><?= $category ?></span>
                                <?php } ?>
                            </div>
                        <?php
                        }
                        ?>

                        <?php
                        $query = "SELECT * from post_atachment where post_id=" . $_GET['post_id'];
                        $result = mysqli_query($connection, $query);

                        if ($result->num_rows) {
                        ?>
                            <h5 class="fw-semibold mt-4">Attachments</h5>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <div>
                                    <a href="<?= "../processes/" . $row['post_attachment_path'] ?>"><?= $row['post_attachment_title'] ?></a>
                                </div>
                        <?php
                            }
                        }

                        ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="table-responsive" id="table-responsive" style="overflow-x:auto;">

    </div>
</section>

<script>
    function show_comments() {
        var post_id = <?= $post_id ?>;
        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("table-responsive").innerHTML = ajax_request.responseText;
                // console.log(ajax_request.responseText);
                $('#commentsTable').DataTable({
                    destroy: true, 
                    responsive: true,
                    autoWidth: false,
                    lengthChange: true,
                    pageLength: 20,
                    scrollX: true
                });
            }
        };

        ajax_request.open("POST", "../processes/comment_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=show_comments_in_dashboard&post_id=" + post_id);
    }

    show_comments();
     function toggle_comment_status(comment_id, current_status) {
        var ajax = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                if (ajax.responseText == "success") {
                    show_comments(); 
                } else {
                    alert("Failed to update comment status.");
                }
            }
        };

        ajax.open("POST", "../processes/comment_process.php");
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("action=toggle_comment_status&comment_id=" + comment_id + "&status=" + current_status);
    }
</script>

<?php
admin_footer();
?>