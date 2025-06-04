<?php

session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

if (!isset($_SESSION['user']['email'])) {
    header("location:../login.php?Please Login First!...");
    die();
} else if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}

admin_header();
admin_navbar();

$type = isset($_GET['type']) ? $_GET['type'] : 'all';
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="heading-2">All Posts</h2>
        <a href="add_post.php" class="btn btn-primary btn-sm">+ New Post</a>
    </div>

    <div class="table-responsive" id="table-responsive" style="overflow-x:auto;">
    </div>
</main>

<script>

    function show_posts(type) {
        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("table-responsive").innerHTML = ajax_request.responseText;
                $('#blogsTable').DataTable({
                    destroy: true,
                    responsive: true,
                    autoWidth: false,
                    lengthChange: true,
                    pageLength: 5,
                    scrollX: true
                });
            }
        };
        ajax_request.open("POST", "../processes/post_process.php", true);
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=show_posts&type=" + type);
    }

    window.onload = function() {
        show_posts("<?= $type ?>");
    };


    function togglePostStatus(post_id, current_status) {
        var new_status = (current_status === 'Active') ? 'Inactive' : 'Active';
        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");
        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                show_posts("<?= $type ?>");
            }
        };
        ajax_request.open("POST", "../processes/post_process.php", true);
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=toggle_post_status&post_id=" + post_id + "&post_status=" + new_status);
    }

    function toggleComments(post_id, current_comment_status) {
        var new_comment_status = (current_comment_status == 1) ? 0 : 1;
        var ajax_request = window.XMLHttpRequest ?
            new XMLHttpRequest() :
            new ActiveXObject("Microsoft.XMLHTTP");
        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                show_posts("<?= $type ?>");
            }
        };
        ajax_request.open("POST", "../processes/post_process.php", true);
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send("action=toggle_comments&post_id=" + post_id + "&allow_comments=" + new_comment_status);
    }

    function deletePost(post_id) {
        if (confirm("Are you sure you want to delete this post?")) {
            var ajax_request = new XMLHttpRequest();
            ajax_request.onreadystatechange = function() {
                if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                    show_posts("<?= $type ?>");
                    alert("Post deleted successfully");
                }
            };
            ajax_request.open("POST", "../processes/post_process.php", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("action=delete_post&post_id=" + post_id);
        }
    }
</script>

<?php
admin_footer();
?>