<?php
session_start();
require("require/layout.php");
require("require/db_connection/connection.php");
user_header();
user_navbar();
if (isset($_SESSION['user'])) {

    $_SESSION['user']['user_id'];
}

// if (!isset($_SESSION['user']['email'])) {
//     header("location:login.php?msg=Please Login First!...");
// }

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

    // die();
}


?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                
                <div class="mb-5">
                    <a href="all_posts.php" class="btn btn-outline-secondary mb-3">
                        <i class="fa fa-arrow-left me-1"></i> Back to Posts
                    </a>
                    <h1 class="fw-bold mb-2"><?= $row['post_title'] ?></h1>
                    <p class="text-muted mb-3">
                        By <strong><?= $row['first_name'] ?></strong> · <?= date('d M Y, g:i A', strtotime($row['created_at'])) ?>
                    </p>
                    <img src="<?= "processes/" . $row['featured_image'] ?>" alt="Post Image" class="img-fluid rounded shadow-sm mb-4" style="height:500px; width:100%; object-fit:cover">

                    <h5 class="fw-semibold">Summary</h5>
                    <p class="text-secondary ps-3 fst-italic border-start border-4 border-primary"><?= $row['post_summary'] ?></p>


                    <h5 class="fw-semibold mt-4">Description</h5>
                    <p class="text-dark"><?= $row['post_description'] ?></p>

                    
                    <?php
                    $cat_query = "SELECT c.category_id, c.category_title 
                                  FROM post_category pc
                                  JOIN category c ON pc.category_id = c.category_id
                                  WHERE pc.post_id = " . (int)$post_id;
                    $cat_result = mysqli_query($connection, $cat_query);
                    if ($cat_result->num_rows > 0): ?>
                        <h5 class="fw-semibold mt-4">Categories</h5>
                        <div class="mb-3">
                            <?php while ($cat_row = mysqli_fetch_assoc($cat_result)): ?>
                                <a href="all_posts.php?category_id=<?= $cat_row['category_id'] ?>&category_title=<?= $cat_row['category_title'] ?>" class="badge bg-primary text-decoration-none me-2"><?= $cat_row['category_title'] ?></a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php
                    $query = "SELECT * from post_atachment where post_id=" . $_GET['post_id'];
                    $result = mysqli_query($connection, $query);
                    if ($result->num_rows) { ?>
                        <h5 class="fw-semibold mt-4">Attachments</h5>
                        <ul class="list-group list-group-flush mb-4">
                            <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                                <li class="list-group-item">
                                    <a href="<?= "processes/" . $data['post_attachment_path'] ?>" class="text-decoration-none"><?= $data['post_attachment_title'] ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>

                <div class="mb-5">
                    <h4 class="mb-3">Leave a Comment</h4>

                    <?php if ($row['is_comment_allowed'] == 1): ?>
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="form-floating mb-3">
                                <span id="comment_msg"></span>
                                <textarea class="form-control" placeholder="Write your comment here" id="comment" name="comment" style="height: 120px" required></textarea>
                                <label for="comment">Your Comment</label>
                            </div>
                            <button type="button" onclick="add_comment()" name="add_comment" id="add_comment" value="add_comment" class="btn btn-primary px-4">Add Comment</button>
                        <?php else: ?>
                            <div class="alert alert-danger">You must <a href="login.php">log in</a> to leave a comment.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">Comments are disabled for this post.</div>
                    <?php endif; ?>

                </div>


                
                <h4 class="mb-4">Comments</h4>
                <div id="comments_section">


                </div>

            </div>
        </div>
    </div>
</section>

<script>
    // function show_comments(){
    //     var ajax_reques
    // }

    function show_comments() {
        var post_id = <?= $post_id ?>;
        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("comments_section").innerHTML = ajax_request.responseText;

            }
        };

        ajax_request.open("POST", "processes/comment_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=show_comments&post_id=" + post_id);
    }

    show_comments();

    function add_comment() {
        var comment = document.getElementById("comment").value;
        var post_id = <?= $post_id ?>;
        var user_id = <?= $_SESSION['user']['user_id'] ?>;

        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("comment_msg").innerHTML = ajax_request.responseText;
                document.getElementById("comment").value = ""; 
                show_comments();
            }
        };

        ajax_request.open("POST", "processes/comment_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=add_comment&post_id=" + post_id + "&comment=" + comment + "&user_id=" + user_id);
    }

    function delete_comment(comment_id) {
        if (!confirm("Are you sure you want to delete this comment?")) return;

        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                alert(ajax_request.responseText);
                show_comments(); 
            }
        };

        ajax_request.open("POST", "processes/comment_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=delete_comment&comment_id=" + comment_id);
    }
</script>



<?php
user_footer();
?>