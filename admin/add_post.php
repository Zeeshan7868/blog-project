<?php
session_start();

require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");
require("../functions/post_functions.php");

if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}



$category_result = fetch_all($connection, 'category');
$categories = [];
$selected_categories = [];
$flag = false;


// while ($category_data = mysqli_fetch_assoc($category_result)) {
//     $categories[] = [
//         'id'    => $category_data['category_id'],
//         'title' => $category_data['category_title'],
//     ];
// }

if (isset($_GET['action']) && $_GET['action'] === 'edit') {

    $flag   = true;
    $postId = $_GET['post_id'];
    $post_result = fetch_by_post_Id($connection, 'post', $postId);
    $row = mysqli_fetch_assoc($post_result);

    $category_query = "SELECT category_id FROM post_category WHERE post_id = $postId";

    $category_query_result = mysqli_query($connection, $category_query);

    while ($category_row = mysqli_fetch_assoc($category_query_result)) {
        $selected_categories[] = $category_row['category_id'];
    }
}
admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="heading-2"> <?= $flag ? "Update Post" : "Create New Post" ?> </h2>
        <a href="posts.php" class="btn btn-outline-secondary btn-sm">← Back to Posts</a>
    </div>

    <form action="../processes/post_process.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Post Title</label>
            <input type="text" name="post_title" class="form-control" placeholder="Enter post title" value="<?= $flag ? $row['post_title'] : "" ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Post Summary</label>
            <textarea name="post_summary" rows="3" class="form-control" placeholder="Write a summary..."><?= $flag ? $row['post_summary'] : "" ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Description</label>
            <textarea name="post_description" rows="6" class="form-control" placeholder="Write your post Description..."><?= $flag ? $row['post_description'] : "" ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Featured Image</label>
            <input type="file" name="featured_image" class="form-control">
        </div>
        <?php
        if ($flag) {
        ?>
            <div class="mb-3">
                <label class="form-label">Current image</label><br>
                <img style="width: 200px;" src="<?= "../processes/" . $row['featured_image'] ?>" alt="">
            </div>

        <?php
        }
        ?>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Select Blog</label>
                <select name="blog_id" id="blogSelect" class="form-select" required >
                    <?php
                    $blog_result = fetch_all($connection, 'blog');

                    while ($blog_row = mysqli_fetch_assoc($blog_result)) {
                    ?>
                        <option <?= $flag && $row['blog_id'] == $blog_row['blog_id'] ? "selected" : "" ?> value="<?= $blog_row['blog_id'] ?>"><?= $blog_row['blog_title'] ?></option>

                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">Select Categories</label>
                <select name="category_ids[]" id="categorySelect" class="form-select" multiple required>
                    <?php
                    while ($category = mysqli_fetch_assoc($category_result)) {

                        $is_selected = in_array($category['category_id'], $selected_categories);
                    ?>
                        <option value="<?= $category['category_id'] ?>" <?= $is_selected ? 'selected' : '' ?>>
                            <?= $category['category_title'] ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>

        </div>


        <div class="row">
            <?php
            if ($flag) {
                $post_status = $row['post_status'];
                $is_comment_allowed = $row['is_comment_allowed'];
            } else {
                $post_status = "";
                $is_comment_allowed = null;
            }
            ?>

            <div class="mb-3 col-md-6">
                <label class="form-label">Status</label>
                <select name="post_status" class="form-select">
                    <option <?= $post_status == "Active" ? "selected" : ""  ?> value="Active">Active</option>
                    <option <?= $post_status == "InActive" ? "selected" : ""  ?> value="InActive">Inactive</option>
                </select>
            </div>

            <div class="mb-3 col-md-6">

                <label class="form-label">Allow Comments</label>
                <select name="allow_comments" class="form-select">
                    <option <?= $flag and $is_comment_allowed == 1 ? "selected" : ""  ?> value="1">Yes</option>
                    <option <?= $flag and $is_comment_allowed == 0 ? "selected" : ""  ?> value="0">No</option>
                </select>
            </div>
        </div>

       

            <?php
            if($flag){

            
            $query = "SELECT * from post_atachment where post_id=" . $_GET['post_id'];
            $result = mysqli_query($connection, $query);

            if ($result->num_rows) {
            ?>
                <h5 class="fw-semibold mt-4">Current Attachments</h5>
                <?php
                while ($at_row = mysqli_fetch_assoc($result)) {
                ?>
                    <div>
                        <a href="<?= "../processes/" . $at_row['post_attachment_path'] ?>"><?= $at_row['post_attachment_title'] ?></a>
                    </div>
            <?php
                }
            }
        }

            ?>

            <div class="mb-3">
                <label class="form-label d-block">Attachments</label>
                <div id="attachmentContainer"></div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAttachment()">+ Add Attachment</button>
            </div>
        <?php
        
        ?>

        <div class="d-flex justify-content-center gap-2">
            <?php
            if ($flag) {
            ?>
                <input type="hidden" name="post_id" value="<?= $_GET['post_id'] ?>">
                <input type="hidden" name="current_image" value="<?= $row['featured_image'] ?>">
                <button type="submit" name="action" value="update_post" class="btn btn-primary">Update Post</button>
            <?php
            } else {
            ?>
                <button type="submit" name="action" value="add_post" class="btn btn-primary">Add Post</button>
            <?php
            }
            ?>

            <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
    </form>
</main>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    function addAttachment() {
        const container = document.getElementById("attachmentContainer");
        const wrapper = document.createElement("div");
        wrapper.className = "row align-items-end mb-3";

        wrapper.innerHTML = `
            <div class="col-md-5 mb-2">
                <label class="form-label">Attachment Title</label>
                <input type="text" name="attachment_titles[]" class="form-control" placeholder="Enter attachment title">
            </div>
            <div class="col-md-5 mb-2">
                <label class="form-label">Upload File</label>
                <input type="file" name="attachments[]" class="form-control">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm" style="position:relative; bottom:10px;" onclick="this.closest('.row').remove()">Remove</button>
            </div>
        `;
        container.appendChild(wrapper);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Single select for blog
        const blogSelect = document.getElementById('blogSelect');
        if (blogSelect) {
            new Choices(blogSelect, {
                searchEnabled: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Choose blog',
                removeItemButton: false, // no remove button for single select
            });
        }

        // Multi select for categories
        const categorySelect = document.getElementById('categorySelect');
        if (categorySelect) {
            new Choices(categorySelect, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Select categories'
            });
        }
    });
</script>

<?php admin_footer(); ?>